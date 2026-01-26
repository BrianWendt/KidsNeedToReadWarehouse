<?php

namespace App\Orchid\Layouts\Contact;

use App\Orchid\Filters;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class ContactSelection extends Selection
{
    /**
     * @var string
     */
    public $template = self::TEMPLATE_LINE;

    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            Filters\ContactSearchFilter::class,
        ];
    }
}
