<?php

namespace App\Orchid\Screens\Address;

use Illuminate\Http\Request;
use Orchid\Screen\Screen;

use App\Models\{
    Address,
    Contact
};

class AddressCreateScreen extends Screen
{
 
    public function query(Address $address, Request $request): iterable
    {
        if ($request->query('contact_id')) {
            $contact = Contact::findOrFail($request->query('contact_id'));
            $address->contact_id = $contact->id;
        } else {
            throw new \Exception('No contact_id provided');
        }

        return [
            'address' => $address,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Add Address to Contact');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            \App\Orchid\Layouts\Address\AddressEditLayout::class
        ];
    }

    public function save(Address $address, \App\Http\Requests\StoreAddressRequest $request)
    {
        $address->fill($request->get('address'))->save();

        \Orchid\Support\Facades\Toast::success(__('Address added'));
        return redirect()->route('app.contact.view', $address->contact_id);
    }
}
