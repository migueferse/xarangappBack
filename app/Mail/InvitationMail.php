<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $event; // Asegúrate de declarar esta propiedad
    public $musician; // y también otras si las usas en la vista

    /**
     * Create a new message instance.
     */
    public function __construct($event, $musician)
    {
        $this->event = $event;
        $this->musician = $musician;
    }
    
    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.invitation')
                    ->subject('Invitación a Evento: ' . $this->event->name)
                    ->with([
                        'eventName' => $this->event->name,
                        'eventDate' => $this->event->date,
                        'eventPlace' => $this->event->place,
                        'musicianName' => $this->musician->name,
                    ]);
    }

}
