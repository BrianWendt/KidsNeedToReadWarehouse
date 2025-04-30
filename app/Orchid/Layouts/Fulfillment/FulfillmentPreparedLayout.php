<?php

namespace App\Orchid\Layouts\Fulfillment;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields;
use Orchid\Screen\Layouts\Rows;

class FulfillmentPreparedLayout extends Rows
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
            Button::make(__('Back to `Preparing`'))
                ->method('backToPreparing')
                ->icon('arrow-left')
                ->class('btn icon-link btn-warning mb-2'),

            Button::make(__('Mark as Shipped'))
                ->method('markAsShipped')
                ->icon('check')
                ->class('btn icon-link btn-primary')
                ->confirm('Are you sure you want to mark this fulfillment as shipped?'),
        ];
    }
}
