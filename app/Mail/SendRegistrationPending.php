<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRegistrationPending extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private function __construct($data)
    {
        $this->data = $data;
        if (strlen($data->event->kicker) > 0) $this->subject('Thank you for registering to ' . $data->event->kicker . ': ' . $data->event->name);
        else $this->subject('Thank you for registering to ' . $data->event->name);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.registration-pending');
    }
}
