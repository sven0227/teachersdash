<?php

namespace Modules\Coupons\Entities;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        "user_id",
        "name",
        "code",
        "discount_amount",
        "expire_date",
        "is_unlimited"
    ];
}
