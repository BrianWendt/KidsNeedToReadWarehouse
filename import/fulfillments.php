<?php

include 'include.php';

echo '<pre>';

$fulfillments_filename = 'fulfillments.csv';
$fulfillments = csv_to_array($fulfillments_filename);

$inventory_filename = 'inventory.csv';
$inventory = csv_to_array($inventory_filename);

// dd($fulfillments[0]);

$statuses = [];
foreach ($fulfillments as $fulfillment) {
    $statuses[$fulfillment['Status']] = $fulfillment['Status'];
}
// dd($statuses);

include 'companies.php';

class Fulfillment extends Model
{
    public $properties = [
        'id' => null,
        'status' => null,
        'program_id' => 1,
        'children_served' => 0,
        'organization_id' => null,
        'contact_id' => null,
        'shipping_contact_id' => null,
        'shipping_address_id' => null,
        'tracking' => '',
        'description' => '',
        'created_at' => DATETIME,
        'updated_at' => DATETIME,
    ];

    public $map = [
        'RefNumber' => 'id',
        'Status' => 'description',
    ];

    public $status_map = [
        'Shipped' => 'shipped',
        'Quote' => 'preparing',
        'Returned' => 'preparing',
        'Partially Returned' => 'preparing',
        'Pending' => 'preparing',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        if (! $data) {
            return;
        }

        $this->properties['status'] = $this->status_map[$data['Status']] ?? 'preparing';

        if ($data['Transaction Date']) {
            $this->properties['created_at'] = $this->properties['updated_at'] = date('Y-m-d H:i:s', strtotime($data['Transaction Date']));
        }
        if ($data['Ship Date']) {
            $this->properties['updated_at'] = date('Y-m-d H:i:s', strtotime($data['Ship Date']));
        }

        $this->properties['organization_id'] = $this->company_id($data);

        $description = $data['BillTo Line2']."\n"
            .$data['BillTo City'].', '.$data['BillTo State'].' '.$data['BillTo PostalCode'];
        if (is_numeric(substr($data['BillTo Line1'], 0, 1))) {
            $description = $data['BillTo Line1']."\n".$description;
        }
        $this->properties['description'] .= "\n".$description;
    }

    public function company_id($data)
    {
        global $companies_list;
        if (isset($companies_list[$data['Company']])) {
            return $companies_list[$data['Company']];
        }
        if (isset($companies_list[$data['BillTo Line1']])) {
            return $companies_list[$data['BillTo Line1']];
        }
        if (isset($companies_list[$data['ShipTo Line1']])) {
            return $companies_list[$data['ShipTo Line1']];
        }
        echo "Company not found\n";
        dd($data);

        return null;
    }
}

class FulfillmentInventory extends Model
{
    public $properties = [
        'fulfillment_id' => null,
        'isbn' => '',
        'book_condition' => 'new',
        'quantity' => 0,
    ];

    public $map = [
        'RefNumber' => 'fulfillment_id',
        'Item' => 'isbn',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        if (! $data) {
            return;
        }
        if (strlen($data['Item']) > 20) {
            $this->properties['isbn'] = substr($data['Item'], 0, 20);
        }

        if (substr($this->properties['isbn'], 0, 5) == '2020-') {
            $this->properties['book_condition'] = 'used';
        }
        $this->properties['quantity'] = intval($data['Quantity']);
    }
}

if (0) {
    $Fulfillments = new Insert('fulfillments', Fulfillment::class, $fulfillments);

    echo "TRUNCATE `fulfillments`;\n";
    echo $Fulfillments->sql();
} else {
    $FulfillmentInventories = new Insert('fulfillment_inventory', FulfillmentInventory::class, $inventory);

    echo "TRUNCATE `fulfillment_inventory`;\n";
    echo $FulfillmentInventories->sql();
}
