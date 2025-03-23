<?php

namespace App\Orchid\Screens\Book;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Layout;

use Orchid\Screen\Fields\Group;

use Orchid\Screen\{
    Actions\Link,
    Screen,
    Sight
};

/**
 * @property \App\Models\Book $book
 */

class BookAddScreen extends Screen
{

    public $isbn;
    public $book;
    public $categories = [];

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(string $isbn): iterable
    {
        $book = null;
        $categories = [];

        $book_response = \App\Services\GoogleBooks\Service::fetchByISBN($isbn);

        if ($book_response) {
            $book = $book_response->toBookModel();
            $categories = $book_response->categories();
        } else {
            $book = new Book([
                'isbn' => $isbn,
            ]);
        }

        return [
            'isbn' => $isbn,
            'book' => $book,
            'categories' => $categories,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Add Book to Database');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::columns([
                \App\Orchid\Layouts\Book\BookEditLayout::class,
                \Orchid\Support\Facades\Layout::legend('book', [
                    Sight::make('Retail')->render(function (Book $book) {
                        return Group::make([
                            Link::make(__('Search Amazon'))
                                ->href($book->amazon)
                                ->target('_blank'),
                            Link::make(__('Search eBay'))
                                ->href($book->ebay)
                                ->target('_blank'),
                        ]);
                    })
                ])
            ])
        ];
    }

    public function search(Request $request)
    {
        $isbn = $request->input('book.isbn');
        $book = Book::where('isbn', $isbn)->first();
        if ($book) {
            return redirect()->route('app.book.edit', $book);
        } else {
            return redirect()->route('app.book.create', ['isbn' => $isbn]);
        }
    }

    public function save(Book $book, StoreBookRequest $request)
    {
        \Orchid\Support\Facades\Toast::success('Book Added.');
        $book->fill($request->input('book'));
        $book->save();
        return redirect()->route('app.inventory.record', $book->isbn);
    }
}
