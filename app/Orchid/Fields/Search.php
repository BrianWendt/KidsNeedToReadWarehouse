<?php

namespace App\Orchid\Fields;

class Search extends Input
{
    public function __construct()
    {
        parent::__construct();
        $this->addBeforeRender(function () {
            $this->attributes['type'] = 'search';

        });
    }
}
