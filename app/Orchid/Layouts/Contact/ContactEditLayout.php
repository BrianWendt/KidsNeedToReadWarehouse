<?php

namespace App\Orchid\Layouts\Contact;

use App\Models\Organization;

use Orchid\Screen\{
    Actions\Button,
    Fields\Input,
    Fields\Select,
    Fields\TextArea,
    Layouts\Rows,
    Field
};

class ContactEditLayout extends Rows
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
        $organizations = Organization::defaultOrder()->get()->pluck('display', 'id')->toArray();
        return [

            Select::make('contact.organization_id')
                ->options($organizations)
                ->title(__('Organization'))
                ->placeholder('No organizations found')
                ->required(),

            Input::make('contact.first_name')
                ->title(__('First Name'))
                ->maxlength(64)
                ->autocomplete('off'),

            Input::make('contact.last_name')
                ->title(__('Last Name'))
                ->maxlength(64)
                ->autocomplete('off'),

            Input::make('contact.preferred_name')
                ->title(__('Preferred Name'))
                ->help(__('The name this contact prefers to be called'))
                ->maxlength(128)
                ->autocomplete('off'),

            Input::make('contact.title')
                ->title(__('Title'))
                ->maxlength(128)
                ->autocomplete('off'),

            Input::make('contact.ein')
                ->title(__('EIN'))
                ->maxlength(20)
                ->autocomplete('off'),

            Textarea::make('contact.note')
                ->title(__('Notes'))
                ->maxlength(600),

            Button::make(__('Save'))
                ->icon('bs.check')
                ->class('btn btn-success btn-block')
                ->method('save')
        ];
    }
}
