<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\GoogleBooks\Service as GoogleBooksService;

class CorrectBooksController extends Controller
{
    public function index()
    {
        $cutoff_date = '2024-07-11 00:00:00';
        $books = Book::where('updated_at', '<', $cutoff_date)
            ->whereRaw('isbn REGEXP "^[0-9]{13}$"')
            ->whereNull('volume_id')
            ->limit(10)
            ->get();

        foreach ($books as $book) {
            $result = GoogleBooksService::fetchByISBN($book->isbn);
            if (! $result) {
                echo "Skipped book: {$book->title} ({$book->isbn})<br/>";
                $book->touch(); // so it stops showing up in this query

                continue;
            }
            $book->volume_id = $result->id();
            $book->title = substr($result->title(), 0, 128);
            $book->author = substr(implode(', ', $result->authors()), 0, 128);
            $book->categories = substr(implode(', ', $result->categories()), 0, 128);
            if ($result->retailPrice()) {
                $book->retail_price = $result->retailPriceAmount();
            }
            if ($result->pageCount()) {
                $book->page_count = $result->pageCount();
            }
            $book->save();
            echo "Updated book: {$book->title}<br/>";
        }
    }
}
