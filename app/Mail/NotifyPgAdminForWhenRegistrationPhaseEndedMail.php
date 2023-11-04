<?php

namespace App\Mail;

use App\Models\Registry;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyPgAdminForWhenRegistrationPhaseEndedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $registry;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Registry $registry, User $user)
    {
        $this->registry = $registry;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('LETS_PLAN_EMAIL'), 'Lets Plan')
            ->view('emails.notifyPgAdminWhenRegistrationPhaseEnds', compact($this->registry, $this->user));
    }
}
