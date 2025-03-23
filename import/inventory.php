<?php
include 'include.php';

echo '<pre>';

$inventory_filename = 'SKUs_Export_07-06-2023.csv';
$inventory = csv_to_array($inventory_filename);

foreach ($inventory as $idx => $data) {
    if (empty($data['Available']) || $data['Available'] == '0') {
        unset($inventory[$idx]);
    }
}

class Inventory extends Model
{
    public $properties = [
        'isbn' => '',
        'book_condition' => 'new',
        'quantity' => 0,
        'fixed_value' => 0,
    ];

    public $map = [
        'Custom SKU' => 'isbn',
        'Cost' => 'fixed_value',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        if (!$data) {
            return;
        }
        $this->properties['quantity'] = intval($data['Available']);
        if (substr($this->properties['isbn'], 0, 5) == '2020-') {
            $this->properties['book_condition'] = 'used';
        } else if (strpos($this->properties['isbn'], 'USED') !== false) {
            $this->properties['book_condition'] = 'used';
        }
    }
}

class Book extends Model
{
    public $properties = [
        'isbn' => '',
        'title' => '',
        'fixed_value' => 0,
    ];

    public $map = [
        'Custom SKU' => 'isbn',
        'Cost' => 'fixed_value',
        'Product Name' => 'title',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        if (!$data) {
            return;
        }
    }
}

if (0) {
    $Inventory = new Insert('inventory', Inventory::class, $inventory);

    echo "TRUNCATE `inventory`;\n";
    echo $Inventory->sql();
} else {
    $Books = new Insert('books', Book::class, $inventory);

    echo "TRUNCATE `books`;\n";
    echo $Books->sql();
}
