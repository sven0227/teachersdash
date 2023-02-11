<?php

namespace Modules\Notifications\Entities;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        "title",
        "content",
        "is_account_setup_payment",
        "status"
    ];
}
