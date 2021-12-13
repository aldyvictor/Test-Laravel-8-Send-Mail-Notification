<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    public static function auth($user)
    {
        $details =
        [
            'email' => $user['email'],
            'link' => env('APP_URL').'/'.$user['email'].'/'.$user['id']
        ];

        Mail::to($user['email'])->send(new SendMail('Welcome', $details));
        return $details;

    }
}
