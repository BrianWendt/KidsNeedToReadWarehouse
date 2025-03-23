<?php

namespace App\Orchid\Screens\Telephone;

use App\Models\Telephone;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class TelephoneEditScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Telephone $telephone, Request $request): iterable
    {
        return [
            'telephone' => $telephone,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Edit Contact Telephone Number');
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
            \App\Orchid\Layouts\Telephone\TelephoneEditLayout::class,
        ];
    }

    public function save(Telephone $telephone, \App\Http\Requests\StoreTelephoneRequest $request)
    {
        $telephone->fill($request->get('telephone'))->save();

        \Orchid\Support\Facades\Toast::success(__('Phone Number updated'));

        return redirect()->route('app.contact.view', $telephone->contact_id);
    }
}
