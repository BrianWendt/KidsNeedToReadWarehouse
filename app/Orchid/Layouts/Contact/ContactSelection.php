<?php


namespace App\Orchid\Layouts\Contact;

use App\Orchid\Filters;
use Orchid\Screen\Layouts\Selection;
use Orchid\Filters\Filter;

class ContactSelection extends Selection
{
    /**
     * @var string
     */
    public $template =  self::TEMPLATE_LINE;

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