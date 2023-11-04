<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BenutzerMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var User
     */
    public $benutzer;
    public $password;
    public $invitedBy;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($benutzer, $password, $invitedBy)
    {
        $this->benutzer = $benutzer;
        $this->password = $password;
        $this->invitedBy = $invitedBy;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $benutzer = $this->benutzer;
        $password = $this->password;
        $invitedBy = $this->invitedBy;
        $url = url('/');

        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Lets Plan Einladung')
            ->view('emails.benutzer-register', compact('password', 'invitedBy', 'url', 'benutzer'));
    }
}
