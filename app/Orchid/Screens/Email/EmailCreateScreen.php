<?php

namespace App\Orchid\Screens\Email;

use Illuminate\Http\Request;
use Orchid\Screen\Screen;

use App\Models\{
    Contact,
    Email
};

class EmailCreateScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Email $email, Request $request): iterable
    {
        if($request->query('contact_id')){
            $contact = Contact::findOrFail($request->query('contact_id'));
            $email->contact_id = $contact->id;
        } else {
            throw new \Exception('No contact_id provided');
        }

        return [
            'email' => $email,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Add Email To Contact');
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
            \App\Orchid\Layouts\Email\EmailEditLayout::class
        ];
    }

    public function save(Email $email, \App\Http\Requests\StoreEmailRequest $request)
    {
        $email->fill($request->get('email'))->save();

        \Orchid\Support\Facades\Toast::success(__('Email Address added'));
        return redirect()->route('app.contact.view', $email->contact_id);
    }
}
