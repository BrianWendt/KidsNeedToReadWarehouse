<?php

namespace App\Orchid\Layouts\Fulfillment;

use Orchid\Screen\Actions;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Layouts\Rows;

class FulfillmentPreparingLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        $fulfillment = $this->query->getContent('fulfillment');

        return [
            Label::make('actions')
                ->title('Actions'),
            Group::make([
                Actions\Link::make(__('Edit Fulfillment Details'))
                    ->route('app.fulfillment.edit', $fulfillment)
                    ->icon('pencil-square')
                    ->class('btn icon-link btn-primary btn-block mb-1'),

                Actions\ModalToggle::make(__('Mark as Ready to Ship'))
                    ->icon('bs.box-arrow-in-up-right')
                    ->class('btn icon-link btn-info btn-block mb-1')
                    ->modal('ready_to_ship_modal')
                    ->method('setReadyToShip'),
            ]),
        ];
    }
}
