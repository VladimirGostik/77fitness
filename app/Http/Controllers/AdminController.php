<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\UserNotificationEmail;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function showEmailForm()
    {
        $users = User::all();
        return view('admin.send_email', compact('users'));
    }

    public function sendEmail(Request $request)
    {
        // Validácia dát
        $request->validate([
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);

        // Odoslanie e-mailu
        Mail::to('admin@example.com')->send(new UserNotificationEmail($request->subject, $request->content));

        // Návratová správa po odoslaní e-mailu
        return redirect()->back()->with('success', 'Email successfully sent to admin.');
    }
}
