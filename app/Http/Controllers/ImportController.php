<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Inventory;

class ImportController extends Controller
{
    public function index()
    {
        echo '<pre>';
        $rows = [];

        if (($open = fopen(storage_path() . '/import.csv', 'r')) !== false) {

            while (($data = fgetcsv($open, 1000, ',')) !== false) {
                $rows[] = $data;
            }

            fclose($open);
        }

        unset($rows[0]);

        $this->importBooks($rows);
        // $this->importInventory($rows);
    }

    public function importBooks($rows)
    {
        foreach ($rows as $book) {
            [$isbn, $title, $fixed_value, $qty] = $book;
            if ($qty < 1) {
                continue;
            }
            try {
                Book::create([
                    'isbn' => $isbn,
                    'title' => $title,
                    'fixed_value' => $fixed_value,
                ]);
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
    }

    public function importInventory($rows)
    {
        foreach ($rows as $book) {
            [$isbn, $title, $fixed_value, $qty] = $book;
            if ($qty < 1) {
                continue;
            }
            try {
                $book_condition = 'new';
                $title = strtolower($title);
                if (strpos($title, 'used') !== false) {
                    $book_condition = 'used';
                }
                Inventory::create([
                    'isbn' => $isbn,
                    'quantity' => $qty,
                    'book_condition' => $book_condition,
                    'enitity_id' => 4, // Import from Lead Commerce
                ]);
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }
    }
}
