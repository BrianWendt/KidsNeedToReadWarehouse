<?php

namespace App\Orchid\Layouts\Fulfillment;

use Orchid\Screen\{
    Actions\Button,
    Fields\Radio,
    Fields\Relation,
    Fields\Select,
    Layouts\Rows,
    Field
};

use App\Orchid\Fields\Input;
use App\Orchid\Fields\ISBN;

use App\Util\RememberedParameter;

class FulfillmentInventoryRecordLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $fields = [
            Relation::make('book_id')
                ->title(__('Search for Book'))
                ->fromModel(\App\Models\Book::class, 'title', 'id')
                ->applyScope('defaultSort', 'updated_at', 'desc')
                ->set('onchange', 'setISBN(this)'),

            ISBN::make('fulfillment_inventory.isbn')
                ->title(__('ISBN'))
                ->placeholder(__('ISBN'))
                ->set('autofocus', true)
                ->required(),

            Input::make('fulfillment_inventory.quantity')
                ->type('number')
                ->title(__('Quantity'))
                ->placeholder(__('Quantity'))
                ->help('Enter the quantity of books.')
                ->min(1)
                ->onEnter('record-inventory-button')
                ->required(),

            Select::make('fulfillment_inventory.book_condition')
                ->title(__('Condition'))
                ->options(config('options.book_conditions'))
                ->help('Select the condition of the books.')
                ->empty('- select condition -')
                ->required()
                ->value(RememberedParameter::getBookCondition('new')),

            Button::make(__('Add to Fulfillment'))
                ->class('btn btn-success')
                ->icon('bs.check-circle')
                ->method('recordInventory')
                ->id('record-inventory-button')
        ];

        //dd(session()->all());

        return $fields;
    }
}
