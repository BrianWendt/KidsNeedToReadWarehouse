<?php

namespace App\Orchid\Screens\Email;

use App\Models\Email;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class EmailEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Email $email, Request $request): iterable
    {

        return [
            'email' => $email,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Edit Contact Email Address');
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
            \App\Orchid\Layouts\Email\EmailEditLayout::class,
        ];
    }

    public function save(Email $email, \App\Http\Requests\StoreEmailRequest $request)
    {
        $email->fill($request->get('email'))->save();

        \Orchid\Support\Facades\Toast::success(__('Email Address added'));

        return redirect()->route('app.contact.view', $email->contact_id);
    }
}
