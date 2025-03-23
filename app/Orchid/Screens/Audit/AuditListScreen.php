<?php

namespace App\Orchid\Screens\Audit;

use App\Models\Audit;

use Orchid\Screen\{
    Actions\Link,
    Screen
};


class AuditListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'audits' => Audit::all(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return __('Audits');
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Create Audit'))
                ->icon('plus-circle')
                ->route('app.audit.create')
                ->class('btn btn-primary')
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
            \App\Orchid\Layouts\Audit\AuditListLayout::class
        ];
    }
}
