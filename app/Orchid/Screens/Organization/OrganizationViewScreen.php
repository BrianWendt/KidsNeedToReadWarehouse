<?php

namespace App\Orchid\Screens\Organization;

use App\Models\Organization;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class OrganizationViewScreen extends Screen
{
    public $organization;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Organization $organization): iterable
    {
        return [
            'organization' => $organization,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->organization->name;
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
                ->route('app.organization.edit', $this->organization)
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
            Layout::legend('organization', [
                Sight::make('name', 'Name'),

                Sight::make('ein', 'Tax ID (EIN)'),

                Sight::make('note', 'Notes'),

                Sight::make('primary_contact_id', 'Primary Contact')->render(function (Organization $organization) {
                    if ($organization->primaryContact) {
                        return Link::make($organization->primaryContact->full_name)
                            ->route('app.contact.view', $organization->primaryContact->id)
                            ->class('text-primary');
                    } else {
                        return 'No primary contact';
                    }
                }),

                Sight::make('contacts', 'Contacts')->render(function (Organization $organization) {
                    $c = $organization->contacts->count();

                    return Link::make($c.' '.($c == 1 ? __('contact') : __('contacts')))
                        ->route('app.contact.list', [
                            'filter' => ['organization_id' => $organization->id],
                        ])
                        ->class('text-primary');
                }),
            ]),
        ];
    }
}
