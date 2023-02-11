<?php

namespace Modules\Events\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Events\Entities\Event;
class EventTemplate extends Model
{
    protected $fillable = [
        "user_id",
        "event_id",
        "name"
    ];

    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
