<?php

namespace App\Orchid\Layouts\Inventory;

use Orchid\Screen\{
    Fields\Group,
    Fields\Label,
    Layouts\Rows,
    Field
};

class InventoryGlanceLayout extends Rows
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
            Group::make([
                Label::make('stats.new_count')
                    ->title(__('New'))
                    ->addBeforeRender($this->renderNumber()),

                Label::make('stats.like_new_count')
                    ->title(__('Like New'))
                    ->addBeforeRender($this->renderNumber()),

                Label::make('stats.used_count')
                    ->title(__('Used'))
                    ->addBeforeRender($this->renderNumber()),

                Label::make('stats.total_count')
                    ->title(__('Total'))
                    ->addBeforeRender($this->renderNumber()),
            ])->set('class', 'col text-center')
        ];
    }

    protected function renderNumber()
    {
        return function () {
            $value = $this->get('value');
            if ($value == 0) {
                $this->set('value', '-');
            } else {
                $this->set('value', number_format($value));
            }
        };
    }
}
