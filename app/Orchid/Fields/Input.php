<?php

namespace App\Orchid\Fields;

use Orchid\Screen\Fields\Input as OrchidInput;

class Input extends OrchidInput
{
    public function __construct()
    {
        parent::__construct();

    }

    public function onEnter($target)
    {
        $this->set('onkeyup', "submit_on_enter(event, '{$target}')");

        return $this;
    }

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        'accept',
        'accesskey',
        'autocomplete',
        'autofocus',
        'checked',
        'disabled',
        'form',
        'formaction',
        'formenctype',
        'formmethod',
        'formnovalidate',
        'formtarget',
        'list',
        'max',
        'maxlength',
        'min',
        'minlength',
        'name',
        'pattern',
        'placeholder',
        'readonly',
        'required',
        'size',
        'src',
        'step',
        'tabindex',
        'type',
        'value',
        'mask',
        'inputmode',
        'onkeyup',
    ];
}
