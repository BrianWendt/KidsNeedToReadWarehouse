<?php

namespace App\Models\Traits;

use App\Models\Book;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property string $isbn
 * @property string $book_condition (new, like_new, used, rb_petsmart, rb_purchased, rb_handmade)
 * @property string $book_condition_display (New, Like New, Used, Reading Buddy Petsmart, Reading Buddy Purchased, Reading Buddy Handmade)
 * @property string $book_condition_group (Purchase, In Kind, Reading Buddy)
 * @property string $book_label (title (isbn) or isbn (not in database))
 * @property \App\Models\Book $book
 */
trait HasBook
{
    public function initializeHasBook()
    {
        $this->appends[] = 'book_condition_display';
        $this->appends[] = 'book_label';
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'isbn', 'isbn');
    }

    public function isbn(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value,
            set: fn (string $value) => preg_replace('/[^A-Za-z0-9]/', '', $value),
        );
    }

    public function bookConditionDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => config('options.book_conditions')[$this->book_condition] ?? '-',
        );
    }

    public function bookConditionGroup(): Attribute
    {
        return Attribute::make(
            get: fn () => collect(config('options.book_condition_groups'))->filter(fn ($conditions) => in_array($this->book_condition, $conditions))->keys()->first()
        );
    }

    public function bookLabel(): Attribute
    {
        if ($this->book) {
            $display = $this->book->title;
        } else {
            $display = $this->isbn . ' (' . __('not in database') . ')';
        }

        return Attribute::make(
            get: fn () => $display,
        );
    }

    public function price(): Attribute
    {
        $price = $this->book ? conditionPrice($this->book, $this->book_condition) : 0;
        if ($this->fixed_value > 0) {
            $price = $this->fixed_value;
        }

        return Attribute::make(
            get: fn () => $price,
        );
    }
}
