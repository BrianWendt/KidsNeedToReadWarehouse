<?php

namespace App\Orchid\Screens\Organization;

use App\Models\Organization;
use Orchid\Screen\Screen;

class OrganizationEditScreen extends Screen
{
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
        return __('Edit Organization');
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
            \App\Orchid\Layouts\Organization\OrganizationEditLayout::class,
        ];
    }

    public function save(Organization $organization, \App\Http\Requests\StoreOrganizationRequest $request)
    {
        $organization->fill($request->get('organization'))->save();

        \Orchid\Support\Facades\Toast::success(__('Organization updated'));

        return redirect()->route('app.organization.view', $organization);
    }
}
