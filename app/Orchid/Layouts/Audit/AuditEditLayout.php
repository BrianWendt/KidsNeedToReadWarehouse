<?php

namespace App\Orchid\Layouts\Audit;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class AuditEditLayout extends Rows
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
            Input::make('audit.label')
                ->maxlength(120)
                ->minlength(2)
                ->title(__('Audit Label'))
                ->required(),

            Button::make(__('Save'))
                ->method('save')
                ->icon('icon-check')
                ->class('btn btn-success mx-1'),
        ];
    }
}
