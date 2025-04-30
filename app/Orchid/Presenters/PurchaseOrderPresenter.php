<?php

namespace App\Orchid\Presenters;

use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

/**
 * @property \App\Models\PurchaseOrder $entity
 */
class PurchaseOrderPresenter extends Presenter implements Searchable
{
    public function label(): string
    {
        return 'Purchase Orders';
    }

    public function title(): string
    {
        return $this->entity->display;
    }

    public function subTitle(): string
    {
        $text = $this->entity->organization ? $this->entity->organization->name : '';
        $text .= ' on ' . $this->entity->received_date;

        return $text;
    }

    public function url(): string
    {
        return route('app.purchase_order.view', $this->entity);
    }

    public function image(): ?string
    {
        return null;
    }

    public function searchQuery(?string $query = null): Builder
    {
        return $this->entity->search($query);
    }

    public function perSearchShow(): int
    {
        return 3;
    }
}
