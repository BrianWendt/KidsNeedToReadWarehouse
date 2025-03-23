<?php

namespace App\Orchid\Layouts\PurchaseOrder;

use Orchid\Screen\Actions;
use Orchid\Screen\Field;
use Orchid\Screen\Fields;
use Orchid\Screen\Layouts\Rows;

class PurchaseOrderCreateLayout extends Rows
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
            Fields\Select::make('purchase_order.organization_id')
                ->title(__('Organization'))
                ->fromQuery(\App\Models\Organization::defaultOrder(), 'display')
                ->required(),

            Fields\DateTimer::make('purchase_order.received_date')
                ->title(__('Received Date'))
                ->format('Y-m-d')
                ->value(date('Y-m-d'))
                ->required(),

            Fields\Select::make('purchase_order.book_condition')
                ->title(__('Default Book Condition'))
                ->options(config('options.book_conditions'))
                ->help(__('Select the condition of the books.')),

            Actions\Button::make(__('Create'))
                ->method('save')
                ->icon('bs.plus-circle')
                ->class('btn btn-success'),
        ];
    }
}
