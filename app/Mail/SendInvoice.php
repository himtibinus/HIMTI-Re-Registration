<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use stdClass;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $data, $message;

    /**
     * Create a new message instance.
     * This is a private method, use SendInvoice.createEmail() instead to create a new message.
     *
     * @return void
     */
    private function __construct($data)
    {
        $this->data = $data;
        if (strlen($data->event->kicker) > 0) $this->subject('Your Payment Invoice for ' . $data->event->kicker . ': ' . $data->event->name);
        else $this->subject('Your Payment Invoice for ' . $data->event->name);
    }

    public static function createEmail($registration){
        // Check whether the event is not free
        $event = DB::table('events')->where('id', $registration->event_id)->first();
        $user = DB::table('users')->where('id', $registration->user_id)->first();

        $data = (object) [
            'registration' => $registration,
            'event' => $event,
            'user' => $user
        ];

        // Skip sending this email if the event is free
        if ($event->price > 0) return new SendInvoice($data);
        else return new SendRegistrationPending($data);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.invoice');
    }
}
