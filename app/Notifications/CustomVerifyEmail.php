<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends BaseVerifyEmail
{
    // protected function verificationUrl($notifiable)
    // {
    //     $temporarySignedURL = \Illuminate\Support\Facades\URL::temporarySignedRoute(
    //         'verification.verify',
    //         now()->addMinutes(60),
    //         ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
    //     );

    //     // Extract query params from the signed backend URL, then point at the frontend
    //     $frontendUrl = rtrim(config('app.frontend_url', 'http://localhost:5173'), '/');

    //     return str_replace(
    //         url('/api/email/verify'),
    //         $frontendUrl . '/verify-email',
    //         $temporarySignedURL
    //     );
    // }
}