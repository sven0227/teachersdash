<?php

namespace Modules\Events\Listeners;

use Modules\Events\Events\EventOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;


class SendMailToAdmin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param EventOrder $event
     * @return void
     */ 
    public function handle(EventOrder $event)
    {
        $guest = $event->guest;

        Mail::send([], [], function ($message) use ($guest) {
            $event = $guest->event;
            $sender_address = config('mail.from.address');
            $sender_name = config("mail.from.name");

            $body = config('events.ORDER_EMAIL_CONTENT');
            $body = str_replace("%event_name%", $event->name, $body);
            $body = str_replace("%event_start_date%", $event->start_date->format("Y-m-d H:i:s"), $body);
            $body = str_replace("%guest_fullname%", $guest->fullname, $body);
            $body = str_replace("%guest_registration_date%", $guest->created_at->format("Y-m-d H:i:s"), $body);
            $body = str_replace("%total_paid_amount%", $guest->total_in_cents / 100, $body);

            
            $message->from($sender_address, $sender_name)
                ->to($event->user->email)
                ->subject("A new event order has arrived.")
                ->setBody($body, "text/html");

        });

    }
}
