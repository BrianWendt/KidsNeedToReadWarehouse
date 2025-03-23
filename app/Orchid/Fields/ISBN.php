<?php

namespace App\Orchid\Fields;

class ISBN extends Search
{
    /**
     * @var string
     */
    protected $view = 'fields.isbn';

    public function __construct()
    {
        $this->addBeforeRender(function () {});
        $this->autocomplete('off');

    }
}
