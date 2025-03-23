<?php

namespace App\Orchid\Layouts\FulfillmentInventory;

use Orchid\Screen\{
    Actions\Button,
    Fields\Group,
    Fields\Input,
    Fields\Select,
    Layouts\Rows,
    Field
};

class FulfillmentInventoryEditLayout extends Rows
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
        
        return [
            Input::make('fulfillment_inventory.isbn')
                ->title(__('ISBN'))
                ->readonly(),

            Input::make('fulfillment_inventory.quantity')
                ->type('number')
                ->title(__('Quantity'))
                ->placeholder(__('Quantity'))
                ->help(__('Enter the quantity of books.'))
                ->min(1)
                ->required(),

            Input::make('fulfillment_inventory.fixed_value')
                ->type('number')
                ->min(0.01)
                ->maxlength(999.99)
                ->step(0.01)
                ->title(__('Unit Price'))
                ->help(__('Enter the value of the book.'))
                ->required(),

            Select::make('fulfillment_inventory.book_condition')
                ->title(__('Condition'))
                ->options(config('options.book_conditions'))
                ->help('Select the condition of the books.'),

            Group::make([
                Button::make(__('Save'))
                    ->method('save')
                    ->icon('check')
                    ->class('btn btn-success'),

                Button::make(__('Remove from Fulfillment'))
                    ->method('delete')
                    ->icon('trash')
                    ->class('btn btn-danger')
            ])
        ];
    }
}
