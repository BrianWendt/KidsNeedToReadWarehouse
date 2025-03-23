<?php

namespace App\Orchid\Screens\Address;

use App\Models\Address;
use Orchid\Screen\Screen;

class AddressEditScreen extends Screen
{

    public function query(Address $address): iterable
    {

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
        return __('Edit Contact Address');
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

        \Orchid\Support\Facades\Toast::success(__('Address updated'));
        return redirect()->route('app.contact.view', $address->contact_id);
    }
}
