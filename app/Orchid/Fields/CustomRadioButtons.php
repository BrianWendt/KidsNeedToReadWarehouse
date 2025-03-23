<?php

namespace App\Orchid\Fields;

use Orchid\Screen\Fields\RadioButtons;

class CustomRadioButtons extends RadioButtons
{
    /**
     * @var string
     */
    protected $view = 'fields.radiobutton';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'type' => 'radio',
        'class' => 'btn-check',
        'label-class' => 'btn btn-outline-primary btn-lg',
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        'accesskey',
        'autofocus',
        'disabled',
        'form',
        'name',
        'required',
        'size',
        'tabindex',
        'type',
        'label-class',
    ];
}
