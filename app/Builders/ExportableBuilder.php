<?php

namespace App\Builders;

use App\Custom\LengthAwareExportablePaginator;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;

class ExportableBuilder extends Builder
{
    /**
     * Paginate the given query.
     *
     * @param  int|null|\Closure  $perPage
     * @param  array|string  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @param  \Closure|int|null  $total
     * @return \Illuminate\Contracts\Pagination\LengthAwareExportablePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        // $builder = clone $this;
        $paginator = parent::paginate($perPage, $columns, $pageName, $page);
        /**
         * @var LengthAwareExportablePaginator $paginator
         */
        $paginator->setBuilder($this);

        return $paginator;
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwareExportablePaginator
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwareExportablePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }
}
