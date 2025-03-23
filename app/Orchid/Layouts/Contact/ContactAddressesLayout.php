<?php

namespace App\Orchid\Layouts\Contact;

use App\Models\Address;

use Orchid\Screen\{
    Actions\Link,
    Actions\Button,
    Layouts\Table,
    TD
};

class ContactAddressesLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'contact.addresses';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $contact = $this->query->get('contact');
        $primary_address = $contact->primary_address;

        return [
            TD::make('name', __('Name'))->cantHide(),

            TD::make('display', __('Address'))
                ->cantHide()
                ->render(function (Address $address) {
                    return \nl2br($address->display);
                }),

            TD::make('actions', __('Actions'))
                ->cantHide()
                ->render(function (Address $address) use ($primary_address)
                {
                    $actions = [];
                    $actions[] = Link::make(__('Edit'))
                        ->route('app.address.edit', $address->id)
                        ->icon('bs.pencil')
                        ->class('btn btn-sm btn-primary');
                        
                    if ($address->id != $primary_address->id) {
                        $actions[] = Button::make(__('Make Primary'))
                            ->method('makePrimary')
                            ->parameters(['primary_address_id' => $address->id])
                            ->icon('bs.star')
                            ->class('btn btn-sm');
                    } else {
                        $actions[] = Link::make(__('Primary'))
                            ->icon('bs.star-fill')
                            ->class('btn btn-success btn-sm');
                    }

                    
                    return \Orchid\Screen\Fields\Group::make($actions)->autoWidth();
                })->width('250px')
        ];
    }
}
