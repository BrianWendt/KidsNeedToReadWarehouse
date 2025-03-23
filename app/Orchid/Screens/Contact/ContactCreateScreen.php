<?php

namespace App\Orchid\Screens\Contact;

use App\Models\Contact;
use Orchid\Screen\Screen;

class ContactCreateScreen extends Screen
{
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
        return __('Create Contact');
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
            \App\Orchid\Layouts\Contact\ContactEditLayout::class,
        ];
    }

    public function save(Contact $contact, \App\Http\Requests\StoreContactRequest $request)
    {
        $contact->fill($request->get('contact'))->save();

        \Orchid\Support\Facades\Toast::success(__('Contact added'));

        return redirect()->route('app.contact.view', $contact->id);
    }
}
