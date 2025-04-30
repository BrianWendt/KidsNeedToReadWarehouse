<?php

namespace App\Orchid\Layouts\Contact;

use App\Models\Email;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ContactEmailsLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'contact.emails';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $contact = $this->query->get('contact');
        $primary_email = $contact->primary_email;

        return [
            TD::make('name', __('Name'))->cantHide(),

            TD::make('display', __('Email'))
                ->cantHide()
                ->render(function (Email $email) {
                    return Link::make($email->address)->href('mailto:' . $email->address);
                }),

            TD::make('actions', __('Actions'))
                ->cantHide()
                ->render(function (Email $email) use ($primary_email) {
                    $actions = [];
                    $actions[] = Link::make(__('Edit'))
                        ->route('app.email.edit', $email->id)
                        ->icon('bs.pencil')
                        ->class('btn btn-sm btn-primary');

                    if ($email->id != $primary_email->id) {
                        $actions[] = Button::make(__('Make Primary'))
                            ->method('makePrimary')
                            ->parameters(['primary_email_id' => $email->id])
                            ->icon('bs.star')
                            ->class('btn btn-sm');
                    } else {
                        $actions[] = Link::make(__('Primary'))
                            ->icon('bs.star-fill')
                            ->class('btn icon-link btn-success btn-sm');
                    }

                    return \Orchid\Screen\Fields\Group::make($actions)->autoWidth();
                })
                ->width('250px'),
        ];
    }
}
