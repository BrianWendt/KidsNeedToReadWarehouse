<?php

namespace App\Services\GoogleBooks;

class VolumeEntity
{
    public $properties = [];

    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    public function toBookModel()
    {
        $book = new \App\Models\Book;
        $book->volume_id = $this->id();
        $book->title = substr($this->title(), 0, 128);
        $book->author = substr(implode(', ', $this->authors()), 0, 128);
        $book->isbn = $this->isbn();
        $book->categories = substr(implode(', ', $this->categories()), 0, 128);
        if ($this->retailPrice()) {
            $book->retail_price = $this->retailPriceAmount();
        }
        if ($this->pageCount()) {
            $book->page_count = $this->pageCount();
        }

        return $book;
    }

    public function id()
    {
        return $this->properties['id'] ?? null;
    }

    public function volumeInfo()
    {
        return $this->properties['volumeInfo'] ?? null;
    }

    public function saleInfo()
    {
        return $this->properties['saleInfo'] ?? null;
    }

    public function title()
    {
        return $this->volumeInfo()['title'] ?? '';
    }

    public function authors()
    {
        return $this->volumeInfo()['authors'] ?? [];
    }

    public function industryIdentifiers()
    {
        return $this->volumeInfo()['industryIdentifiers'] ?? [];
    }

    public function pageCount()
    {
        return $this->volumeInfo()['pageCount'] ?? null;
    }

    public function categories()
    {
        return $this->volumeInfo()['categories'] ?? [];
    }

    public function isbn()
    {
        return $this->isbn13() ?? $this->isbn10();
    }

    public function isbn13()
    {
        foreach ($this->industryIdentifiers() as $identifier) {
            if ($identifier['type'] === 'ISBN_13') {
                return $identifier['identifier'];
            }
        }

        return false;
    }

    public function isbn10()
    {
        foreach ($this->industryIdentifiers() as $identifier) {
            if ($identifier['type'] === 'ISBN_10') {
                return $identifier['identifier'];
            }
        }

        return false;
    }

    public function retailPrice()
    {
        return $this->saleInfo()['retailPrice'] ?? null;
    }

    public function retailPriceAmount()
    {
        return $this->retailPrice()['amount'] ?? null;
    }
}
