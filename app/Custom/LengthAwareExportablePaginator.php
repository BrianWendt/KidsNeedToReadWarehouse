<?php

namespace App\Custom;

use Illuminate\Database\Eloquent\Builder;

class LengthAwareExportablePaginator extends \Illuminate\Pagination\LengthAwarePaginator
{
    private $_builder;

    public function builder() : Builder
    {
        return $this->_builder;
    }

    public function setBuilder(Builder $builder)
    {
        $this->_builder = $builder;
    }

    function  __serialize()
    {
        return [];
    }
}
