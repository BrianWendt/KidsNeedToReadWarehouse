<?php

namespace App\Orchid\Layouts\Fulfillment;

use Orchid\Screen\{
    Actions\Button,
    Fields,
    Layouts\Rows,
    Field
};

class FulfillmentTrackingLayout extends Rows
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
        return [
            Fields\Input::make('tracking')
                ->title(__('Tracking Number'))
                ->placeholder(__('Tracking Number'))
                ->help('Enter the tracking number for this fulfillment.')
                ->maxlength(20),

            Button::make(__('Set Tracking Number'))
                ->method('setTracking')
                ->icon('check')
                ->class('btn btn-success'),
        ];
    }
}
