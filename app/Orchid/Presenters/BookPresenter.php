<?php

namespace App\Orchid\Presenters;

use Laravel\Scout\Builder;
use Orchid\Screen\Contracts\Searchable;
use Orchid\Support\Presenter;

class BookPresenter extends Presenter implements Searchable
{
    public function label(): string
    {
        return 'Books';
    }

    public function title(): string
    {
        return $this->entity->title ?? '-na-';
    }

    public function subTitle(): string
    {
        return $this->entity->author ?? '-unknown author-';
    }

    public function url(): string
    {
        return route('app.book.edit', $this->entity);
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
