<?php

namespace App\Orchid\Layouts\Email;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class EmailEditLayout extends Rows
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
            Input::make('email.contact_id')
                ->type('hidden'),

            Label::make('email.contact.display')
                ->title(__('Contact Name')),

            Select::make('email.name')
                ->title(__('Type'))
                ->options([
                    'Business',
                    'Personal',
                ])
                ->allowAdd()
                ->maxlength(128)
                ->required()
                ->autocomplete('off'),

            Input::make('email.address')
                ->type('email')
                ->title(__('Email Address'))
                ->placeholder('name@domain.com')
                ->maxlength(128)
                ->required()
                ->autocomplete('off'),

            Input::make('email.note')
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
