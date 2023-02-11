<?php

namespace Modules\Events\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Events\Entities\Guest;

class EventOrder
{
    use SerializesModels;

    public $guest;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Guest $guest)
    {
        $this->guest = $guest;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
