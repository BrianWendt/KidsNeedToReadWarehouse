<?php

namespace App\Orchid\Layouts\Address;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class AddressEditLayout extends Rows
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
            Input::make('address.contact_id')
                ->type('hidden'),

            Label::make('address.contact.display')
                ->title(__('Contact Name')),

            Select::make('address.name')
                ->title(__('Type'))
                ->options([
                    'Office',
                    'Home',
                ])
                ->allowAdd()
                ->maxlength(128)
                ->required()
                ->autocomplete('off'),

            Input::make('address.street1')
                ->title(__('Street'))
                ->maxlength(128)
                ->required()
                ->autocomplete('off'),

            Input::make('address.street2')
                ->title(__('Line 2'))
                ->placeholder('unit/apt/etc.')
                ->maxlength(128)
                ->autocomplete('off'),

            Input::make('address.city')
                ->title(__('City'))
                ->maxlength(80)
                ->required()
                ->autocomplete('off'),

            Input::make('address.state')
                ->title(__('State'))
                ->maxlength(2)
                ->required()
                ->autocomplete('off'),

            Input::make('address.zipcode')
                ->title(__('Zipcode'))
                ->maxlength(5)
                ->mask('99999')
                ->required()
                ->autocomplete('off'),

            Button::make(__('Save'))
                ->method('save')
                ->icon('check')
                ->class('btn icon-link btn-success'),
        ];
    }
}
