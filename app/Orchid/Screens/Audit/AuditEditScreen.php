<?php

namespace App\Orchid\Screens\Audit;

use App\Models\Audit;
use Orchid\Screen\Screen;

class AuditEditScreen extends Screen
{
    public $audit;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Audit $audit): iterable
    {
        return [
            'audit' => $audit,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return __('Edit Audit:') . '#' . $this->audit->id;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
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
            \App\Orchid\Layouts\Audit\AuditEditLayout::class,
        ];
    }

    public function save(Audit $audit, \App\Http\Requests\StoreAuditRequest $request)
    {
        $audit->fill($request->get('audit'))->save();

        \Orchid\Support\Facades\Toast::success(__('Audit updated'));

        return redirect()->route('app.audit.list', $audit);
    }

    public function close(Audit $audit)
    {
        $audit->closed_at = now();
        $audit->save();

        \Orchid\Support\Facades\Toast::success(__('Audit closed'));

        return redirect()->route('app.audit.list');
    }
}
