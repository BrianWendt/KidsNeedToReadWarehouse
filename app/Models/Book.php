<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Facades\DB;

/**
 * @property string $title
 * @property string $isbn
 * @property string $volume_id Google Books ID
 * @property string $author
 * @property string $categories
 * @property float $retail_price
 * @property string $retail_price_display $0.00
 * @property float $fixed_value
 * @property string $fixed_value_display $0.00
 * @property int $page_count
 * @property string $image_thumbnail URL to thumbnail image
 * @property string $ebay URL to eBay search
 * 
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory[] $inventory
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory[] $new_inventory
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Inventory[] $used_inventory
 * 
 * @property int $inventory_quantity
 * @property int $new_inventory_quantity
 * @property int $like_new_inventory_quantity
 * @property int $used_inventory_quantity
 * 
 * @property float $new_value (new inventory quantity * retail price)
 * @property float $like_new_value (like new inventory quantity * retail price * 0.8)
 * @property float $used_value (used inventory quantity * retail price * 0.5)
 * 
 * @method static \Illuminate\Database\Eloquent\Builder fetchInventory(?string $select) adds subqueries for new_inventory_quantity and used_inventory_quantity
 */

class Book extends AppModel
{

    use \App\Models\Traits\Exports,
        \Laravel\Scout\Searchable;

    protected $attributes = [

    ];

    protected $fillable = [
        'title',
        'isbn',
        'volume_id',
        'author',
        'retail_price',
        'fixed_value',
        'page_count',
    ];

    protected $allowedFilters = [
        'title' => \App\Filters\Types\Search::class,
        'isbn' => \Orchid\Filters\Types\Where::class,
        'author' => \Orchid\Filters\Types\Like::class,
    ];

    protected $casts = [
        'retail_price' => 'float',
        'fixed_value' => 'float',
        'page_count' => 'integer',
    ];

    protected $appends = [
        
    ];

    protected $inventory_conditions = null;

    public function isbn(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            set: fn (string $value) => trim(preg_replace('/[^A-Za-z0-9]/', '', $value)),
        );
    }

    public function display(): Attribute
    {
        return Attribute::make(
            get: fn () => "<em>{$this->title}</em> by {$this->author}",
        );
    }

    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Inventory::class, 'isbn', 'isbn');
    }

    public function scopeFetchInventory(Builder $query, $select = '*')
    {
        return $query->addSelect($select)
            ->addSelect(DB::raw('(SELECT SUM(quantity) FROM inventory WHERE isbn = books.isbn and book_condition = "new" AND deleted_at IS NULL) as new_inventory_quantity'))
            ->addSelect(DB::raw('(SELECT SUM(quantity) FROM inventory WHERE isbn = books.isbn and book_condition = "like_new" AND deleted_at IS NULL) as like_new_inventory_quantity'))
            ->addSelect(DB::raw('(SELECT SUM(quantity) FROM inventory WHERE isbn = books.isbn and book_condition = "used" AND deleted_at IS NULL) as used_inventory_quantity'));
    }

    public function getInventoryQuantity($condition = null)
    {
        if (!$this->inventory_conditions) {
            $this->inventory_conditions = $this->inventory()->groupBy('book_condition')->select('book_condition', DB::raw('SUM(quantity) as quantity'))->pluck('quantity', 'book_condition')->toArray();
        }
        if ($condition == null) {
            return array_sum($this->inventory_conditions);
        }
        return $this->inventory_conditions[$condition] ?? 0;
    }

    public function inventoryQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getInventoryQuantity(),
        );
    }

    public function newInventoryQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getInventoryQuantity('new'),
        );
    }

    public function likeNewInventoryQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getInventoryQuantity('like_new'),
        );
    }

    public function usedInventoryQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getInventoryQuantity('used'),
        );
    }

    public function conditionPrice($condition)
    {
        return conditionPrice($this, $condition);
    }

    /**
     * Builds the attribute for the value of a condition
     *
     * @param string $condition
     * @return Attribute
     */
    private function conditionValueAttribute(string $condition)
    {
        $qty = $this->getInventoryQuantity($condition);
        $price = $this->conditionPrice($condition);
        return Attribute::make(
            get: fn () => $qty * $price,
        );
    }

    public function newValue(): Attribute
    {
        return $this->conditionValueAttribute('new');
    }

    public function likeNewValue(): Attribute
    {
        return $this->conditionValueAttribute('like_new');
    }

    public function usedValue(): Attribute
    {
        return $this->conditionValueAttribute('used');
    }

    public function imageThumbnail(): Attribute
    {
        return Attribute::make(
            get: fn () => "http://books.google.com/books/content?id={$this->volume_id}&printsec=frontcover&img=1&zoom=5&source=gbs_api"
        );
    }

    public function ebay(): Attribute
    {
        return Attribute::make(
            get: fn () => "https://www.ebay.com/sch/i.html?_nkw={$this->isbn}+book"
        );
    }

    public function amazon(): Attribute
    {
        return Attribute::make(
            get: fn () => "https://www.amazon.com/s?k={$this->isbn}+book"
        );
    }

    public function retailPriceDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->retail_price ? '$' . number_format($this->retail_price, 2) : 'n/a'
        );
    }

    public function fixedValueDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fixed_value ? '$' . number_format($this->fixed_value, 2) : 'n/a'
        );
    }

    /**
     * Get the presenter for the model.
     *
     * @return IdeaPresenter
     */
    public function presenter()
    {
        return new \App\Orchid\Presenters\BookPresenter($this);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $array = $this->toArray();
 
        // Customize the data array...
 
        return $array;
    }
}
