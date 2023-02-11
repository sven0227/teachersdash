<?php

namespace Modules\Upsell\Entities;

use Illuminate\Database\Eloquent\Model;

class Upsell extends Model
{
    protected $fillable = [
        "user_id",
        "title",
        "price_items",
        "image",
        "description"
    ];
    protected $casts = [
        'price_items' => 'array',
    ];
    public function getPrices() {
        return $this->price;
    }
}
