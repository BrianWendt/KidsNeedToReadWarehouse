<?php

namespace App\Services\GoogleBooks;

use Illuminate\Support\Facades\Http;

class Service
{
    public static function http()
    {
        return Http::baseUrl('https://www.googleapis.com/books/v1/');
    }

    /**
     * Search for books
     *
     * @param  string  $query
     * @param  string  $scope
     * @return \App\Services\GoogleBooks\VolumeEntity[]
     */
    public static function search($query, $scope = 'limit', int $maxResults = 40): array
    {
        $params = [
            'q' => $query,
            'key' => 'AIzaSyDsMhfWoGfhU3cOibPZN5XQFXohzzSYCNU',
            'maxResults' => $maxResults,
        ];
        switch ($scope) {
            case 'limit':
                $params['fields'] = 'items(id,volumeInfo(title,authors,industryIdentifiers,pageCount,categories),saleInfo(retailPrice))';
                break;
            case 'all':
                break;
        }
        $response = self::http()->get('volumes', $params);
        if (! $response->successful()) {
            if (env('APP_DEBUG')) {
                dd($response->json());
            }

            return [];
        }
        $data = $response->json();

        if (empty($data['items'])) {
            return [];
        }
        $return = [];
        foreach ($data['items'] as $item) {
            $return[] = new VolumeEntity($item);
        }

        return $return;
    }

    /**
     * Search for books by ISBN
     *
     * @param  string  $isbn
     * @param  string  $scope
     * @return \App\Services\GoogleBooks\VolumeEntity[]
     */
    public static function searchByISBN($isbn, $scope = 'limit'): array
    {
        return self::search("isbn:$isbn", $scope, 10);
    }

    /**
     * Fetch a book by ISBN
     */
    public static function fetchByISBN(string $isbn): ?VolumeEntity
    {
        $data = self::searchByISBN($isbn, 'limit');

        return $data[0] ?? null;
    }
}
