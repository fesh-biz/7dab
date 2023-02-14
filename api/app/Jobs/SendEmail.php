<?php

namespace App\Jobs;

use App\Mail\Auth\EmailVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $email;
    protected Mailable $mailable;
    
    public function __construct(string $email, Mailable $mailable)
    {
        $this->email = $email;
        $this->mailable = $mailable;
    }
    
    public function handle()
    {
        Mail::to($this->email)->send($this->mailable);
    }
}
