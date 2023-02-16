<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected string $hashedId;
    protected string $login;
    
    public function __construct(string $hashedId, string $login)
    {
        $this->hashedId = $hashedId;
        $this->login = $login;
    }

    public function build(): Mailable
    {
        $hashedId = $this->hashedId;
        $login = $this->login;
        
        return $this->from('noreply@terevenky.com','Terevenky')
            ->subject('Terevenky. Підтвердження email.')
            ->view('emails.auth.email-confirmation', compact('hashedId', 'login'));
    }
}
