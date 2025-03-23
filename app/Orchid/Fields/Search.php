<?php

namespace App\Orchid\Fields;

use App\Orchid\Fields\Input;

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
