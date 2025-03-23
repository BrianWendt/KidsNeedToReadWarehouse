<?php

namespace App\Orchid\Layouts\Telephone;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class TelephoneEditLayout extends Rows
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
            Input::make('telephone.contact_id')
                ->type('hidden'),

            Label::make('telephone.contact.display')
                ->title(__('Contact Name')),

            Select::make('telephone.name')
                ->title(__('Type'))
                ->options([
                    'Office',
                    'Business Cell',
                    'Personal Cell',
                    'Fax',
                ])
                ->allowAdd()
                ->maxlength(128)
                ->required()
                ->autocomplete('off'),

            Input::make('telephone.number')
                ->title(__('Number'))
                ->maxlength(20)
                ->required()
                ->autocomplete('off')
                ->mask('999-999-9999'),

            Input::make('telephone.extension')
                ->title(__('Extension'))
                ->maxlength(20)
                ->autocomplete('off'),

            Input::make('telephone.note')
                ->title(__('Notes'))
                ->placeholder(__('Notes'))
                ->maxlength(600)
                ->autocomplete('off'),

            Button::make(__('Save'))
                ->method('save')
                ->icon('check')
                ->class('btn btn-success'),
        ];
    }
}
