<?php

namespace App\Orchid\Layouts\Inventory;

use App\Models\PurchaseOrder;
use App\Orchid\Fields\Input;
use App\Util\RememberedParameter;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class InventoryRecordLayout extends Rows
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
        $purchase_order_id = RememberedParameter::getPurchaseOrderId();
        $fields = [];

        $fields['inventory.isbn'] = Input::make('inventory.isbn')
            ->type('text')
            ->title(__('ISBN'))
            ->placeholder(__('Enter ISBN'))
            ->readonly();

        $fields['inventory.quantity'] = Input::make('inventory.quantity')
            ->type('number')
            ->title(__('Quantity'))
            ->placeholder(__('Enter Quantity'))
            ->help(__('Enter the quantity of books.'))
            ->set('autofocus', true)
            ->onEnter('record')
            ->value(1)
            ->required();

        $fields['inventory.book_condition'] = Select::make('inventory.book_condition')
            ->title(__('Condition'))
            ->options(config('options.book_conditions'))
            ->help(__('Select the condition of the books.'))
            ->value(RememberedParameter::getBookCondition('new'));

        $fields['inventory.fixed_value'] = Input::make('inventory.fixed_value')
            ->title(__('Fixed Price'))
            ->type('number')
            ->min(0.01)
            ->maxlength(999.99)
            ->step(0.01)
            ->help(__('Overrides calculated price based on condition.'));

        /**
         * @var \Illuminate\Database\Query\Builder $query
         */
        $query = PurchaseOrder::limit(20);
        if ($purchase_order_id) {
            $query->orderBy(DB::raw('id = ' . $purchase_order_id), 'DESC');
        }
        $query->orderBy('id', 'desc');
        $fields['inventory.entity_id'] =
            Select::make('inventory.entity_id')
                ->title(__('PO #'))
                ->fromQuery($query, 'display', 'id')
                ->help(__('Select the Purchase Order.'));

        $fields['inventory.note'] = TextArea::make('inventory.note')
            ->title(__('Note'))
            ->placeholder(__('Enter Note'))
            ->maxlength(200)
            ->help(__('Enter any notes about the books.'));

        $fields['submit'] = Button::make(__('Record'))
            ->class('btn icon-link btn-success')
            ->icon('bs.check-circle')
            ->method('save')
            ->id('record');

        return $fields;
    }
}
