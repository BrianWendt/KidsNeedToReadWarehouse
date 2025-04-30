<?php

namespace App\Orchid\Layouts\Contact;

use App\Models\Contact;
use App\Models\Organization;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ContactListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'contacts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('actions', __(''))
                ->render(function (Contact $contact) {
                    return Link::make('View')
                        ->route('app.contact.view', $contact->id)
                        ->icon('bs.eye');
                }),

            TD::make('first_name', __('First Name'))
                ->sort()
                ->filter(Input::make()),

            TD::make('last_name', __('Last Name'))
                ->sort()
                ->filter(Input::make()),

            TD::make('organization_id', __('Organization'))
                ->filter(
                    Relation::make()
                        ->fromModel(Organization::class, 'name')
                        ->applyScope('defaultOrder'),
                    ['label' => 'display']
                )
                ->filterValue(function ($organization_id) {
                    return Organization::find($organization_id)->name ?? $organization_id;
                })
                ->render(function (Contact $contact) {
                    return Link::make($contact->organization->name)
                        ->route('app.organization.view', $contact->organization)
                        ->class('text-primary');
                }),

            TD::make('title', __('Title')),

            TD::make('updated_at', __('Update date'))
                ->render(function ($model) {
                    return $model->updated_at->format('Y-m-d H:i A');
                }),
        ];
    }
}
