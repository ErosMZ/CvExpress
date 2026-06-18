<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Activa tu cuenta en CvXpress')
            ->replyTo(config('mail.from.address'), config('mail.from.name'))
            ->withSymfonyMessage(function ($message) use ($notifiable) {
                $message->getHeaders()
                    ->addTextHeader('X-Entity-Ref-ID', sha1($notifiable->email . now()->timestamp))
                    ->addTextHeader('List-Unsubscribe', '<mailto:' . config('mail.from.address') . '?subject=unsubscribe>');
            })
            ->view('emails.verify', [
                'url'  => $verificationUrl,
                'name' => $notifiable->name,
            ]);
    }
}
