<?php

namespace App\Orchid\Layouts\Fulfillment;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class FulfillmentCreateLayout extends Rows
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
        $organizations = \App\Models\Organization::getAsOptions('display', 'id');
        $programs = \App\Models\Program::getAsOptions('display', 'id');
        $initiatives = \App\Models\Initiative::getAsOptions('display', 'id');

        return [
            Select::make('fulfillment.program_id')
                ->title(__('Program'))
                ->empty(__('Select a program'))
                ->options($programs),

            Select::make('fulfillment.initiative_id')
                ->title(__('Initiative'))
                ->empty(__('Select an initiative'))
                ->options($initiatives),

            Select::make('fulfillment.organization_id')
                ->title(__('Organization'))
                ->empty(__('Select an organization'))
                ->help(__('Contact is selected on the next step.'))
                ->options($organizations),

            Button::make(__('Create'))
                ->method('save')
                ->icon('bs.plus-circle')
                ->class('btn icon-link btn-success'),
        ];
    }
}
