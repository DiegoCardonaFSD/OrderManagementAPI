<?php

namespace App\Repositories\Email;

use Illuminate\Support\Facades\Mail;

class EmailSenderRepository
{
    public function send(string $to, mixed $content): void
    {
        Mail::to($to)->send($content);
    }
}
