<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function message(Request $request)

    {
        //taking data from the form
        $data = $request->all();

        //validation
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ], [
            'email.required' => 'email is mandatory',
            'email.email' => 'Email address is not valid',
            'subject.required' => 'Message must have an object',
            'message.required' => 'Message must have a content',
        ]);

        // if there are errors, they'll go back
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        //making email
        $mail = new ContactMessageMail(
            sender: $data['email'],
            subject: $data['subject'],
            content: $data['message'],
        );

        //sending the email
        Mail::to(env('MAIL_TO_ADDRESS'))->send($mail);

        // ok no content!
        return response(null, 204);
    }
}
