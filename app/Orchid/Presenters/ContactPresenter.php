<?php

namespace App\Orchid\Presenters;

use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class ContactPresenter extends Presenter implements Searchable
{
    public function label(): string
    {
        return 'Contacts';
    }

    public function title(): string
    {
        return $this->entity->full_name ?? '-na-';
    }

    public function subTitle(): string
    {
        return $this->entity->organization ? $this->entity->organization->name : '-unknown organization-';
    }

    public function url(): string
    {
        return route('app.contact.edit', $this->entity);
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
