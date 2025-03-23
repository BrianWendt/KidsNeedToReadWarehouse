<?php

namespace App\Orchid\Layouts\Audit;

use App\Orchid\Fields\ISBN;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class AuditInventoryRecordLayout extends Rows
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
            ISBN::make('audit_inventory.isbn')
                ->type('text')
                ->title(__('ISBN'))
                ->placeholder(__('Enter ISBN'))
                ->set('autofocus', true),

            Input::make('audit_inventory.quantity')
                ->type('number')
                ->title(__('Quantity'))
                ->placeholder(__('Enter Quantity'))
                ->help(__('Enter the quantity of books.')),

            Button::make(__('Record'))
                ->class('btn btn-success')
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }
}
