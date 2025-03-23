<?php

namespace App\Orchid\Screens\Book;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Orchid\Screen\Screen;

/**
 * @property \App\Models\Book $book
 */
class BookEditScreen extends Screen
{
    public $book;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Book $book): iterable
    {
        return [
            'book' => $book,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Edit Book');
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
            \App\Orchid\Layouts\Book\BookEditLayout::class,
        ];
    }

    public function save(Book $book, StoreBookRequest $request)
    {
        $book->fill($request->input('book'));
        $book->save();
        \Orchid\Support\Facades\Toast::success(__('Updated Book.'));

        return redirect()->route('app.inventory.record', $book->isbn);
    }
}
