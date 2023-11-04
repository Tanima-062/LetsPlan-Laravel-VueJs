<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    /**
     * The callback that should be used to create the reset password URL.
     *
     * @var \Closure|null
     */
    public static $createUrlCallback;
    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        //
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return $this->buildMailMessage($this->resetUrl($notifiable));

        $url = url('/');
        return (new MailMessage)
            ->subject('Lets Plan Passwort zurücksetzen')
            ->view('emails.reset-password', ['user' => $notifiable, 'url' => $this->resetUrl($notifiable)])
            ;
    }
    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('Lets Plan Passwort zurücksetzen')
            ->line('Sie haben Ihr Passwort vergessen und möchten dies zurücksetzen? Ihr neues Passwort können Sie einrichten indem Sie innerhalb von 60 Minuten auf den folgenden Link klicken:')
            ->action('Passwort zurücksetzen', $url)
            ->line(Lang::get('Nach :count Minuten verfällt dieser Link und muss aus Sicherheitsgründen neu beantragt werden.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('Sollten Sie dies nicht angefordert haben, können Sie diese E-Mail ignorieren. Ihr Passwort ändert sich erst, wenn Sie auf den obigen Link zugreifen und ein neues erstellen.'));
    }
    /**
     * Get the reset URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetUrl($notifiable)
    {
        return url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
    /**
     * Set a callback that should be used when creating the reset password button URL.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}
