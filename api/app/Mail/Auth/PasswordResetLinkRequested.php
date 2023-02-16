<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetLinkRequested extends Mailable
{
    use Queueable, SerializesModels;
    public array $resetPasswordData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $resetPasswordData)
    {
        $this->resetPasswordData = $resetPasswordData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): Mailable
    {
        $email = $this->resetPasswordData['email'];
        $token = $this->resetPasswordData['token'];

        $login = User::whereEmail($email)->first()->login;
        $actionUrl = env('APP_URL') . '/auth/reset-password/' . $token;

        return $this->from('noreply@terevenky.com','Terevenky')
            ->subject('Terevenky. Відновлення паролю.')
            ->to($email)
            ->view(
                'emails.auth.resetting-password',
                compact('actionUrl', 'login')
            );
    }
}
