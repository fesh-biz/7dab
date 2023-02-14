<?php

namespace App\Services\Jobs;

use App\Jobs\SendEmail;
use Illuminate\Mail\Mailable;

class MailService
{
    public function sendEmail(string $email, Mailable $mailable)
    {
        SendEmail::dispatch($email, $mailable);
    }
}