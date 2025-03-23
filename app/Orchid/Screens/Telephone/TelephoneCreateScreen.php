<?php

namespace App\Orchid\Screens\Telephone;

use Illuminate\Http\Request;
use Orchid\Screen\Screen;

use App\Models\{
    Contact,
    Telephone
};

class TelephoneCreateScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Telephone $telephone, Request $request): iterable
    {
        if($request->query('contact_id')){
            $contact = Contact::findOrFail($request->query('contact_id'));
            $telephone->contact_id = $contact->id;
        } else {
            throw new \Exception('No contact_id provided');
        }

        return [
            'telephone' => $telephone,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Add Telephone To Contact');
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
            \App\Orchid\Layouts\Telephone\TelephoneEditLayout::class
        ];
    }

    public function save(Telephone $telephone, \App\Http\Requests\StoreTelephoneRequest $request)
    {
        $telephone->fill($request->get('telephone'))->save();

        \Orchid\Support\Facades\Toast::success(__('Phone Number added'));
        return redirect()->route('app.contact.view', $telephone->contact_id);
    }
}
