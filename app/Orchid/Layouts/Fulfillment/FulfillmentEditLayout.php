<?php

namespace App\Orchid\Layouts\Fulfillment;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class FulfillmentEditLayout extends Rows
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
        $fulfillment = $this->query->getContent('fulfillment');
        if (! is_array($fulfillment)) {
            $fulfillment = $fulfillment->toArray() ?? [];
        }
        $organization_id = $fulfillment['organization_id'];

        $contacts = \App\Models\Contact::where('organization_id', $organization_id)->get()->pluck('full_name', 'id')->toArray();

        $addresses = [];
        if ($fulfillment['shipping_contact_id']) {
            $addresses = \App\Models\Address::where('contact_id', $fulfillment['shipping_contact_id'])->get()->pluck('display', 'id')->toArray();
        }

        $programs = \App\Models\Program::getAsOptions('display', 'id');
        $initiatives = \App\Models\Initiative::getAsOptions('display', 'id');

        return [
            Input::make('fulfillment.children_served')
                ->type('number')
                ->min(0)
                ->title(__('Children Served'))
                ->empty(__('Number of children served')),

            Select::make('fulfillment.contact_id')
                ->title(__('Contact'))
                ->empty(__('Select a contact'))
                ->options($contacts),

            Select::make('fulfillment.program_id')
                ->title(__('Program'))
                ->empty(__('Select a program'))
                ->options($programs),

            Select::make('fulfillment.initiative_id')
                ->title(__('Initiative'))
                ->empty(__('Select an initiative'))
                ->options($initiatives),

            Select::make('fulfillment.shipping_contact_id')
                ->title(__('Shipping Contact'))
                ->options($contacts)
                ->empty(__('Select a contact')),

            Select::make('fulfillment.shipping_address_id')
                ->title(__('Shipping Address'))
                ->options($addresses)
                ->empty(__('Select an address'))
                ->canSee(! empty($fulfillment['shipping_contact_id'])),

            Link::make(__('Add address to Shipping Contact'))
                ->route('app.address.create', ['contact_id' => $fulfillment['shipping_contact_id']])
                ->icon('bs.plus-circle')
                ->class('btn btn-link mb-3')
                ->canSee(! empty($fulfillment['shipping_contact_id'])),

            TextArea::make('fulfillment.description')
                ->title(__('Fulfillment Description'))
                ->placeholder(__('optional'))
                ->rows(3),

            Button::make(__('Save'))
                ->method('save')
                ->icon('check')
                ->class('btn btn-success'),

            Input::make('fulfillment.organization_id')
                ->type('hidden')
                ->required(),
        ];
    }
}
