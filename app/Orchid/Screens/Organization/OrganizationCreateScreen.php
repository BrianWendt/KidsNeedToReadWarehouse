<?php

namespace App\Orchid\Screens\Organization;

use App\Models\Organization;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;

class OrganizationCreateScreen extends Screen
{
    public function query(Organization $organization, Request $request): iterable
    {
        return [
            'organization' => $organization,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Add Organization');
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
            \App\Orchid\Layouts\Organization\OrganizationEditLayout::class
        ];
    }

    public function save(Organization $organization, \App\Http\Requests\StoreOrganizationRequest $request)
    {
        $organization->fill($request->get('organization'))->save();

        \Orchid\Support\Facades\Toast::success(__('Organization added'));
        return redirect()->route('app.organization.view', $organization);
    }
}
