<?php

namespace App\Http\Controllers;

use App\Mail\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function mail() {
        return view('mail');
    }

    public function mailpost(Request $req) {
        // return var_dump($req->mailAddress);
        Mail::to($req->mailAddress)->send(new SendEmail($req->mailAddress));

        return '전송 완료';
        // return redirect()->back()->with('success', '메일 전송 완료');
    }
}
