<?php

namespace PortedCheese\ProductVariation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use PortedCheese\BaseSettings\Traits\ShouldSlug;

class OrderState extends Model
{
    use ShouldSlug;

    protected $fillable = [
        "title",
        "key",
        "slug",
    ];

    /**
     * Значение ключа.
     *
     * @param bool $updating
     */
    public function fixKey($updating = false)
    {
        if ($updating && ($this->original["key"] == $this->key)) {
            return;
        }
        if (empty($this->key)) {
            $key = "{$this->slug}";
        }
        else {
            $key = $this->key;
        }
        $key = Str::slug($key);
        $buf = $key;
        $i = 1;
        if ($updating) {
            $id = $this->id;
        }
        else {
            $id = 0;
        }
        while (\App\OrderState::query()
            ->select("id")
            ->where("key", $buf)
            ->where("id", "!=", $id)
            ->count()
        ) {
            $buf = $key . "-" . $i++;
        }
        $this->key = $buf;
    }
}
