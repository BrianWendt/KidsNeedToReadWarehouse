<?php

namespace App\Orchid\Presenters;

use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

/**
 * @property \App\Models\Fulfillment $entity
 */
class FulfillmentPresenter extends Presenter implements Searchable
{
    public function label(): string
    {
        return 'Fulfillments';
    }

    public function title(): string
    {
        return $this->entity->display;
    }

    public function subTitle(): string
    {
        return $this->entity->display;
    }

    public function url(): string
    {
        return route('app.fulfillment.view', $this->entity);
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
