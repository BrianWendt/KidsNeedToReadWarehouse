<?php

namespace App\Orchid\Layouts\Contact;

use App\Models\Telephone;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ContactTelephonesLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'contact.telephones';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $contact = $this->query->get('contact');
        $primary_telephone = $contact->primary_telephone;

        return [
            TD::make('name', __('Name'))->cantHide(),

            TD::make('display', __('Telephone'))
                ->cantHide()
                ->render(function (Telephone $telephone) {
                    return \nl2br($telephone->display);
                }),

            TD::make('actions', __('Actions'))
                ->cantHide()
                ->render(function (Telephone $telephone) use ($primary_telephone) {
                    $actions = [];
                    $actions[] = Link::make(__('Edit'))
                        ->route('app.telephone.edit', $telephone->id)
                        ->icon('bs.pencil')
                        ->class('btn btn-sm btn-primary');

                    if ($telephone->id != $primary_telephone->id) {
                        $actions[] = Button::make(__('Make Primary'))
                            ->method('makePrimary')
                            ->parameters(['primary_telephone_id' => $telephone->id])
                            ->icon('bs.star')
                            ->class('btn btn-sm');
                    } else {
                        $actions[] = Link::make(__('Primary'))
                            ->icon('bs.star-fill')
                            ->class('btn btn-success btn-sm');
                    }

                    return \Orchid\Screen\Fields\Group::make($actions)->autoWidth();
                })->width('250px'),
        ];
    }
}
