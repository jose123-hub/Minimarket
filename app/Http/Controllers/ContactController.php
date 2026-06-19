<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|min:2',
            'email'   => 'required|email',
            'subject' => 'required|min:3',
            'message' => 'required|min:10',
        ]);

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        Mail::to('admin@example.com')->send(new ContactMail($data));

        return redirect('/contact')->with('success', 'Message sent successfully! We will get back to you soon.');
    }
}