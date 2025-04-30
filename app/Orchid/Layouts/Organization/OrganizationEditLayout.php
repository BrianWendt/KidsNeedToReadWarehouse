<?php

namespace App\Orchid\Layouts\Organization;

use App\Models\Contact;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class OrganizationEditLayout extends Rows
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
        $organization = $this->query->get('organization');

        $contacts = Contact::organizationId($organization->id)
            ->get()
            ->pluck('display_name', 'id')
            ->toArray();

        return [
            Input::make('organization.name')
                ->title(__('Name'))
                ->placeholder('Name of the organization')
                ->maxlength(128)
                ->required(),

            Input::make('organization.ein')
                ->title(__('Tax ID (EIN)'))
                ->placeholder('EIN')
                ->mask('99-9999999'),

            TextArea::make('organization.note')
                ->title(__('Notes'))
                ->maxlength(600),

            Select::make('organization.primary_contact_id')
                ->options($contacts)
                ->title(__('Primary Contact'))
                ->placeholder('No contacts found for this organization')
                ->canSee($organization->exists),

            CheckBox::make('organization.starred')
                ->placeholder('★ ' . __('Starred'))
                ->sendTrueOrFalse(),

            Button::make(__('Save'))
                ->method('save')
                ->icon('check')
                ->class('btn icon-link btn-success'),
        ];
    }
}
