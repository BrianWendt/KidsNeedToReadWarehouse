<?php

namespace App\Orchid\Layouts\PurchaseOrder;

use Orchid\Screen\Actions;
use Orchid\Screen\Field;
use Orchid\Screen\Fields;
use Orchid\Screen\Layouts\Rows;

class PurchaseOrderEditLayout extends Rows
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
        $purchase_order = $this->query->getContent('purchase_order');
        if (! is_array($purchase_order)) {
            $purchase_order = $purchase_order->toArray() ?? [];
        }
        $organization_id = $purchase_order['organization_id'];

        $contacts = \App\Models\Contact::where('organization_id', $organization_id)->get()->pluck('full_name', 'id')->toArray();

        $addresses = \App\Models\Address::where('contact_id', $purchase_order['contact_id'])->get()->pluck('display', 'id')->toArray();

        $telephones = \App\Models\Telephone::where('contact_id', $purchase_order['contact_id'])->get()->pluck('display', 'id')->toArray();

        $emails = \App\Models\Email::where('contact_id', $purchase_order['contact_id'])->get()->pluck('address', 'id')->toArray();

        return [

            Fields\Select::make('purchase_order.book_condition')
                ->title(__('Default Book Condition'))
                ->options(config('options.book_conditions'))
                ->help(__('Select the condition of the books.')),

            Fields\Group::make([
                Fields\Input::make('purchase_order.organization.name')
                    ->title(__('Organization'))
                    ->readonly(),
                Actions\ModalToggle::make(__(''))
                    ->title('Change Organization')
                    ->modal('organizationModal')
                    ->method('changeOrganization')
                    ->icon('pencil')
                    ->class('btn btn-secondary'),
            ]),

            Fields\DateTimer::make('purchase_order.received_date')
                ->title(__('Received Date'))
                ->format('Y-m-d')
                ->required(),

            Fields\Select::make('purchase_order.contact_id')
                ->title(__('Contact'))
                ->empty(__('Select a contact'))
                ->options($contacts),

            Fields\Select::make('purchase_order.address_id')
                ->title(__('Address'))
                ->empty(__('Select an address'))
                ->options($addresses),

            Fields\Select::make('purchase_order.telephone_id')
                ->title(__('Telephone'))
                ->empty(__('Select a telephone'))
                ->options($telephones),

            Fields\Select::make('purchase_order.email_id')
                ->title(__('Email'))
                ->empty(__('Select an email'))
                ->options($emails),

            Fields\TextArea::make('purchase_order.note')
                ->title(__('Purchase Order Note'))
                ->placeholder(__('optional'))
                ->rows(3),

            Fields\Input::make('purchase_order.id')
                ->type('hidden')
                ->required(),

            Fields\Input::make('purchase_order.organization_id')
                ->type('hidden')
                ->required(),

            Fields\Group::make([
                Actions\Button::make(__('Save'))
                    ->method('save')
                    ->icon('check')
                    ->class('btn btn-success w-100'),
                Actions\Link::make(__('Cancel'))
                    ->route('app.purchase_order.view', $purchase_order['id'])
                    ->icon('x')
                    ->class('btn btn-danger'),
            ]),
        ];
    }
}
