<?php

include 'include.php';

echo '<pre>';

$customers_filename = 'Customers_Export_08-11-2024.csv';
$customers = csv_to_array($customers_filename);

// dd($customers[0]);

include 'companies.php';

class Contact extends Model
{
    public $properties = [
        'id' => null,
        'organization_id' => null,
        'first_name' => '',
        'last_name' => '',
        'preferred_name' => '',
        'title' => '',
        'ein' => '',
        'note' => '',
        'created_at' => DATETIME,
        'updated_at' => DATETIME,
    ];

    public $map = [
        'ID' => 'id',
        'First Name' => 'first_name',
        'Last Name' => 'last_name',
        'Role' => 'title',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        if (! $data) {
            return;
        }
        global $companies_list;
        $company_name = $data['Company Name'] ?? $data['Name Address'];
        if ($company_name) {
            $this->properties['organization_id'] = $companies_list[$company_name];
        }

    }
}

class Address extends Model
{
    public $properties = [
        'id' => null,
        'contact_id' => null,
        'street1' => '',
        'street2' => '',
        'city' => '',
        'state' => '',
        'zipcode' => '',
        'created_at' => DATETIME,
        'updated_at' => DATETIME,
    ];

    public $map = [
        'ID' => 'contact_id',
        'Address 1' => 'street1',
        'Address 2' => 'street2',
        'City' => 'city',
        'Zip' => 'zipcode',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        if (! $data) {
            return;
        }
        global $states;
        $State = trim($data['State']);
        if (strlen($State) == 2) {
            $this->properties['state'] = $State;
        } elseif (in_array($State, $states)) {
            $this->properties['state'] = array_search($State, $states);
        } else {
            echo 'State not found: '.$State."\n";
            dd($data);
        }
    }
}

class Telephone extends Model
{
    public $properties = [
        'id' => null,
        'contact_id' => null,
        'name' => 'Primary',
        'number' => '',
        'extension' => '',
        'note' => '',
        'created_at' => DATETIME,
        'updated_at' => DATETIME,
    ];

    public $map = [
        'ID' => 'contact_id',
        'Phone' => 'number',
    ];
}

class Email extends Model
{
    public $properties = [
        'id' => null,
        'contact_id' => null,
        'name' => 'Primary',
        'address' => '',
        'note' => '',
        'created_at' => DATETIME,
        'updated_at' => DATETIME,
    ];

    public $map = [
        'ID' => 'contact_id',
        'Email Address' => 'address',
    ];
}

class Organization extends Model
{
    public $properties = [
        'id' => null,
        'name' => '',
        'ein' => '',
        'primary_contact_id' => null,
        'note' => '',
        'starred' => 0,
        'created_at' => DATETIME,
        'updated_at' => DATETIME,
    ];

    public $map = [
        'Company Name' => 'name',
        'ID' => 'primary_contact_id',
        'Default Order Notes' => 'note',
    ];

    public function __construct($data = [])
    {
        parent::__construct($data);
        if (! $data) {
            return;
        }
        $this->properties['ein'] = extract_ein($data['Default Order Notes']);

        global $companies_list;
        if (empty($this->properties['name'])) {
            $this->properties['name'] = $data['Name Address'];
        }
        $this->properties['id'] = $companies_list[$this->properties['name']];
    }
}

$Contacts = new Insert('contacts', Contact::class, $customers);

$Addresses = new Insert('addresses', Address::class, $customers);

$Telephones = new Insert('telephones', Telephone::class, $customers);

$Emails = new Insert('emails', Email::class, $customers);

$Organizations = new Insert('organizations', Organization::class, $customers);

echo 'Truncate `contacts`;'."\n";
echo $Contacts->sql();
echo 'Truncate `addresses`;'."\n";
echo $Addresses->sql();
echo 'Truncate `telephones`;'."\n";
echo $Telephones->sql();
echo 'Truncate `emails`;'."\n";
echo $Emails->sql();
echo 'Truncate `organizations`;'."\n";
echo $Organizations->sql();
