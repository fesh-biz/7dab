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
    
    public function __construct(string $hashedId)
    {
        $this->hashedId = $hashedId;
    }

    public function build(): Mailable
    {
        $hashedId = $this->hashedId;
        
        return $this->from('no-reply@terevenky.com')->view('emails.auth.email-confirmation', compact('hashedId'));
    }
}
