<?php

include 'include.php';

echo '<pre>';

$orders_filename = 'Orders_Export_08-11-2024.csv';
$data = csv_to_array($orders_filename);

$fulfillments = [array_keys($data[0])];

$inventory = [
    [
        'RefNumber',
        'Item',
        'Quantity',
    ],
];

foreach ($data as $row) {
    $id = $row['RefNumber'];
    $fulfillments[$id] = $row;
    $inventory[] = [
        $id,
        $row['Item'],
        $row['Quantity'],
    ];
}

array_to_csv('fulfillments.csv', $fulfillments);
array_to_csv('inventory.csv', $inventory);

echo 'Done';
