<?php

namespace App\Orchid\Layouts\Audit;

use App\Util\RememberedParameter;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class AuditInventoryBulkRecordLayout extends Rows
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
            Select::make('book_condition')
                ->title(__('Condition'))
                ->options(config('options.book_conditions'))
                ->help(__('Select the condition of the books.'))
                ->value(RememberedParameter::getBookCondition('new'))
                ->required(),

            TextArea::make('bulk')
                ->title(__('Bulk Record'))
                ->placeholder(__('isbn[tabspace]quantity'))
                ->help(__('From the Trohestar scanner'))
                ->rows(10),

            Button::make(__('Bulk Record'))
                ->class('btn icon-link btn-success')
                ->icon('bs.upc-scan')
                ->method('bulkSave'),
        ];
    }
}
