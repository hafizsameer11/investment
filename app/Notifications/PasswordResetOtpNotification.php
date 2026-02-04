<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetOtpNotification extends Notification
{
    use Queueable;

    public string $otp;

    public int $expiresMinutes;

    public function __construct(string $otp, int $expiresMinutes = 10)
    {
        $this->otp = $otp;
        $this->expiresMinutes = $expiresMinutes;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Reset Verification Code')
            ->line('Use the verification code below to reset your password.')
            ->line('Verification Code: ' . $this->otp)
            ->line('This code will expire in ' . $this->expiresMinutes . ' minutes.')
            ->line('If you did not request a password reset, you can ignore this email.');
    }
}
