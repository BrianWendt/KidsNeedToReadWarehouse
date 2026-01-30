<?php

namespace App\Orchid\Layouts\PurchaseOrder;

use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;
use App\Orchid\Filters;

class PurchaseOrderSelection extends Selection
{
    /**
     * @var string
     */
    public $template = self::TEMPLATE_LINE;

    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [Filters\CreatedAtFilter::class, Filters\OrganizationFilter::class, Filters\ContactFilter::class];
    }
}
