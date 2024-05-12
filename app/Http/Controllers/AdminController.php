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
        $recipients = [];
        if ($request->recipient === 'All Users') {
            // Get all user emails from database (replace with your logic)
            $recipients = User::all();
            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)->send(new UserNotificationEmail($request->subject, $request->content));
                sleep(1); // Introduce a 1-second delay between emails

            }
        } else if ($request->recipient === 'Trainers') {
            // Get trainer emails from database (replace with your logic)
            $recipients = User::where('role', 2);
            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)->send(new UserNotificationEmail($request->subject, $request->content));
                sleep(1); // Introduce a 1-second delay between emails

            }
        } else if ($request->recipient === 'Clients') {
            // Get client emails from database (replace with your logic)
            $recipients = User::where('role', 1);
            foreach ($recipients as $recipient) {
                Mail::to($recipient->email)->send(new UserNotificationEmail($request->subject, $request->content));
                sleep(1); // Introduce a 1-second delay between emails

            }
        } else {
            // Specific user email should be in the recipient field from Blade template
            Mail::to($request->recipient)->send(new UserNotificationEmail($request->subject, $request->content));
        }

        // Odoslanie e-mailu

        // Návratová správa po odoslaní e-mailu
        return redirect()->back()->with('success', 'Email successfully sent to users.');
    }
}
