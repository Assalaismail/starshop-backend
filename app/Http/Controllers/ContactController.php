<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\mail\ContactFormMail;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;


class ContactController extends ApiController
{
    public function sendContactForm(Request $request)
{
    $data = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'message' => 'required',
    ]);

    // Send email
    Mail::to('ismailassala1@gmail.com')->send(new ContactFormMail($data));

    // Redirect or return a response
    return $this->apiResponse($data, self::STATUS_OK, __('Response ok!'));
}

}
