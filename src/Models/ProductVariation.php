<?php

namespace PortedCheese\ProductVariation\Models;

use App\Cart;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariation extends Model
{
    protected $fillable = [
        "sku",
        "price",
        "sale_price",
        "description",
        "sale",
        "disabled_at",
    ];

    /**
     * Товар.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Корзины.
     *
     * @return BelongsToMany
     */
    public function carts()
    {
        if (class_exists(Cart::class)) {
            return $this->belongsToMany(Cart::class)
                ->withPivot("quantity")
                ->withTimestamps();
        }
        else {
            return new BelongsToMany($this->newQuery(), $this, "", "", "", "", "");
        }
    }

    /**
     * Формат цены.
     *
     * @return string
     */
    public function getHumanPriceAttribute()
    {
        if ($this->price - intval($this->price) > 0) {
            return number_format($this->price, 2, ",", " ");
        }
        else {
            return number_format($this->price, 0, ",", " ");
        }
    }

    /**
     * Формат цены.
     *
     * @return string
     */
    public function getHumanSalePriceAttribute()
    {
        if ($this->sale_price - intval($this->sale_price) > 0) {
            return number_format($this->sale_price ?? 0, 2, ",", " ");
        }
        else {
            return number_format($this->sale_price ?? 0, 0, ",", " ");
        }
    }

    /**
     * Скидка.
     *
     * @return mixed
     */
    public function getDiscountAttribute()
    {
        if ($this->sale) {
            return $this->sale_price - $this->price;
        }
        else {
            return 0;
        }
    }

    /**
     * Формат скидки.
     *
     * @return string
     */
    public function getHumanDiscountAttribute()
    {
        if ($this->discount - intval($this->discount) > 0) {
            return number_format($this->discount, 2, ",", " ");
        }
        else {
            return number_format($this->discount, 0, ",", " ");
        }
    }

    /**
     * Поправить артикул.
     *
     * @param bool $updating
     */
    public function fixSku($updating = false)
    {
        if ($updating && ($this->original["sku"] == $this->sku)) {
            return;
        }
        if (empty($this->sku)) {
            $product = $this->product;
            $category = $product->category;
            $sku = "{$category->slug}#{$product->slug}";
        }
        else {
            $sku = $this->sku;
        }
        $sku = str_replace(" ", "#", $sku);
        $buf = $sku;
        $i = 1;
        if ($updating) {
            $id = $this->id;
        }
        else {
            $id = 0;
        }
        while (\App\ProductVariation::query()
            ->select("id")
            ->where("sku", $buf)
            ->where("id", "!=", $id)
            ->count()
        ) {
            $buf = $sku . "-" . $i++;
        }
        $this->sku = $buf;
    }
}
