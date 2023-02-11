<?php

namespace Modules\Events\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Mail;
use Modules\Events\Entities\Guest;
use Carbon\Carbon;
use Modules\Email\Entities\EmailReminderTypes;
use Modules\Email\Entities\EmailTemplate;
use Modules\Sms\Entities\SmsReminderTypes;
use Modules\Sms\Entities\SmsTemplate;
use Modules\Sms\Entities\SmsAccount;
use Twilio\Rest\Client;
class PaymentRequest extends Command
{
    protected $sms_account;
    protected $sms_template;
    protected $email_template;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'events:payment-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for email reminder to be sent to guests that do not proceed payment.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    public function sendPaymentRequest($guest) {
        Mail::send([], [], function($message) use($guest) {
            $event = $guest->event;
            $body = $this->email_template->description;
            $body = str_replace('%event_name%', $event->name, $body);
            $body = str_replace('%event_description%', $event->description, $body);
            $body = str_replace('%guest_fullname%', $guest->fullname, $body);
            $body = str_replace('%guest_email%', $guest->email, $body);
            $body = str_replace('%guest_ticket_name%', $guest->ticket_name, $body);
            $body = str_replace('%guest_ticket_price%', $guest->ticket_price, $body);
            $body = str_replace('%guest_ticket_currency%', $guest->ticket_currency, $body);
            $body = str_replace('%event_address%', $event->address, $body);
            $body = str_replace('%event_checkout_link%', '<a href="https://1da.sh/e/checkout/' . $guest->guest_code . '">Go to checkout page</a>', $body);
            if ($event->start_date) $body = str_replace('%event_start_date%', $event->start_date->format('Y-m-d H:i:s'), $body);
            $body = str_replace("%user_company_name%", $event->user->company, $body);
            $body = str_replace("%user_company_email%", $event->user->user_company_email, $body);
            $body = str_replace("%user_company_phone%", $event->user->user_company_phone, $body);
            $body = str_replace("%user_company_website%", $event->user->user_company_website, $body);

            $sender_address = config('mail.from.address');
            $sender_name = config('mail.from.name');

            if ($event->email_sender_email) $sender_address = $event->email_sender_email;
            if ($event->email_sender_name) $sender_name = $event->email_sender_name;

            $message->from($sender_address, $sender_name)
                ->to($guest->email)
                ->subject($this->email_template->subject)
                ->setBody($body, "text/html");
        });

        $user = $guest->event->user;
        if ($user->sms_status && $user->sms_balance > $this->sms_account->sms_fee) {
            $user->update(["sms_balance" => ($user->sms_balance - $this->sms_account->sms_fee)]);
            $client = new Client($this->sms_account->twilio_sid, $this->sms_account->twilio_token);
            $body = $this->sms_template->description;
            $body = str_replace('%event_name%', $guest->event->name, $body);
            $body = str_replace('%event_description%', $guest->event->description, $body);
            $body = str_replace('%guest_fullname%', $guest->fullname, $body);
            $body = str_replace('%guest_email%', $guest->email, $body);
            $body = str_replace('%guest_ticket_name%', $guest->ticket_name, $body);
            $body = str_replace('%guest_ticket_price%', $guest->ticket_price, $body);
            $body = str_replace('%guest_ticket_currency%', $guest->ticket_currency, $body);
            $body = str_replace('%event_address%', $guest->event->address, $body);
            $body = str_replace("%event_checkout_link%", "https://1da.sh/e/checkout/" . $guest->guest_code, $body);
            if ($guest->event->start_date)
                $body = str_replace('%event_start_date%', $guest->event->start_date->format('Y-m-d H:i:s'), $body);
            $body = str_replace("%user_company_name%", $guest->event->user->company, $body);
            $body = str_replace("%user_company_email%", $guest->event->user->user_company_email, $body);
            $body = str_replace("%user_company_phone%", $guest->event->user->user_company_phone, $body);
            $body = str_replace("%user_company_website%", $guest->event->user->user_company_website, $body);
            $body .= " reply STOP to unsubscribe.";

            $client->messages->create($guest->phone, [
                "from" => $this->sms_account->twilio_number,
                "body" => $body
            ]);
        }
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sms_account = SmsAccount::first();
        $type = SmsReminderTypes::where(["type" => "Payment Not Completed"])->first();
        $sms_template = SmsTemplate::where(["reminder_id" => $type->id, "type" => "default"])->first();
        $this->sms_template = $sms_template;

        $type = EmailReminderTypes::where(["type" => "Payment Not Completed"])->first();
        $email_template = EmailTemplate::where(["reminder_id" => $type->id, "type" => "default"])->first();
        $this->email_template = $email_template;
        
        $current = Carbon::now();
        $guests = Guest::where("created_at", ">", $current->subDays(3)->toDateTimeString())
            ->where("created_at", "<=", $current->subDays(2)->toDateTimeString())
            ->where("is_paid", 0)
            ->get();
        
        foreach ($guests as $guest) {
            $this->sendPaymentRequest($guest);
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
