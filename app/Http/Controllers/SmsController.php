<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index()
    {
        return view('sms.index');
    }

    public function send(Request $request)
    {
        // Implement SMS sending logic here
        return back()->with('success', 'SMS sent successfully');
    }
}
