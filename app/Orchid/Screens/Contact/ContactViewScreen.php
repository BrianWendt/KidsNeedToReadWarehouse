<?php

namespace App\Orchid\Screens\Contact;

use App\Models\Contact;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class ContactViewScreen extends Screen
{
    public $contact;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Contact $contact): iterable
    {
        return [
            'contact' => $contact,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->contact->full_name;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Edit'))
                ->icon('pencil-square')
                ->route('app.contact.edit', $this->contact)
                ->class('btn btn-primary'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('contact', [
                Sight::make('full_name', __('Full Name')),
                Sight::make('organization.name', __('Organization'))->render(function (Contact $contact) {
                    return Link::make($contact->organization->name)->route('app.organization.view', $contact->organization)->class('text-primary');
                }),
                Sight::make('title', __('Title')),
                Sight::make('ein', __('EIN')),
                Sight::make('note', __('Notes')),
            ]),
            Layout::tabs([
                __('Email Addresses') => [
                    \App\Orchid\Layouts\Contact\ContactEmailsLayout::class,
                    Layout::rows([
                        Link::make(__('Add Email Address'))
                            ->route('app.email.create', ['contact_id' => $this->contact->id])
                            ->icon('bs.plus')
                            ->class('btn btn-sm btn-success'),
                    ]),
                ],

                __('Addresses') => [
                    \App\Orchid\Layouts\Contact\ContactAddressesLayout::class,
                    Layout::rows([
                        Link::make(__('Add Address'))
                            ->route('app.address.create', ['contact_id' => $this->contact->id])
                            ->icon('bs.plus')
                            ->class('btn btn-sm btn-success'),
                    ]),
                ],

                __('Telephones') => [
                    \App\Orchid\Layouts\Contact\ContactTelephonesLayout::class,
                    Layout::rows([
                        Link::make(__('Add Telephone Number'))
                            ->route('app.telephone.create', ['contact_id' => $this->contact->id])
                            ->icon('bs.plus')
                            ->class('btn btn-sm btn-success'),
                    ]),
                ],
            ]),

        ];
    }

    public function makePrimary(Contact $contact, Request $request)
    {
        $contact->fill($request->all())->save();
        \Orchid\Support\Facades\Toast::success(__('Primary updated'));

        return back();
    }
}
