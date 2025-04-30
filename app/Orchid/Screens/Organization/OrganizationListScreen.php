<?php

namespace App\Orchid\Screens\Organization;

use App\Models\Organization;
use Orchid\Screen\Screen;

class OrganizationListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'organizations' => Organization::filters()->defaultSortRaw('`starred` DESC, `name` ASC')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Organizations');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            \Orchid\Screen\Actions\Link::make(__('Add Organization'))
                ->icon('plus-circle')
                ->route('app.organization.create')
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
            \App\Orchid\Layouts\Organization\OrganizationListLayout::class,
        ];
    }
}
