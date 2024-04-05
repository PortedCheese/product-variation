<?php

namespace PortedCheese\ProductVariation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PortedCheese\BaseSettings\Traits\ShouldSlug;

class Measurement extends Model
{
    use ShouldSlug;

    protected $fillable = [
        "title",
        "short",
        "slug",
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function variations(){
        return $this->hasMany(\App\ProductVariation::class);
    }
}
