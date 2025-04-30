<?php

namespace App\Orchid\Screens\Contact;

use App\Models\Contact;
use Orchid\Screen\Screen;

class ContactListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'contacts' => Contact::filters()->defaultSort('first_name')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Contacts');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $filters = request()->get('filter', []);
        $organizationId = $filters['organization_id'] ?? null;

        return [
            \Orchid\Screen\Actions\Link::make(__('Add Contact'))
                ->icon('bs.plus-circle')
                ->route('app.contact.create', [
                    'organization_id' => $organizationId,
                ])
                ->class('btn icon-link btn-primary'),
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
            \App\Orchid\Layouts\Contact\ContactListLayout::class,
        ];
    }
}
