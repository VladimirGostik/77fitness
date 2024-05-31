<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\EmailLog;
use Illuminate\Http\Request; 
use App\Mail\UserNotificationEmail;
use Illuminate\Support\Facades\Mail; 

class AdminController extends Controller
{
    // Metóda na zobrazenie formulára pre email
    public function showEmailForm()
    {
        $users = User::all(); // Získanie všetkých používateľov
        return view('admin.send_email', compact('users')); // Vrátenie pohľadu s dátami používateľov
    }

    // Metóda na odoslanie emailov
    public function sendEmail(Request $request)
    {
        // Validácia dát z requestu
        $request->validate([
            'subject' => 'required|string',
            'content' => 'required|string',
        ]);
    
        $recipients = []; // Inicializácia poľa príjemcov
        if ($request->recipient === 'All Users') {
            // Získanie všetkých používateľov
            $recipients = User::all();
            foreach ($recipients as $recipient) {
                if ($recipient->receive_notifications) {
                    // Odoslanie emailu a zaznamenanie logu
                    Mail::to($recipient->email)->send(new UserNotificationEmail($request->subject, $request->content));
                    $this->logEmail($recipient->email, $request->subject, $request->content);
                    sleep(1); // Pauza, aby sa emaily neposielali príliš rýchlo
                }
            }
        } else if ($request->recipient === 'Trainers') {
            $recipients = User::where('role', 2)->get();
            foreach ($recipients as $recipient) {
                if ($recipient->receive_notifications) {
                    Mail::to($recipient->email)->send(new UserNotificationEmail($request->subject, $request->content));
                    $this->logEmail($recipient->email, $request->subject, $request->content);
                    sleep(1); 
                }
            }
        } else if ($request->recipient === 'Clients') {
            $recipients = User::where('role', 1)->get();
            foreach ($recipients as $recipient) {
                if ($recipient->receive_notifications) {
                    Mail::to($recipient->email)->send(new UserNotificationEmail($request->subject, $request->content));
                    $this->logEmail($recipient->email, $request->subject, $request->content);
                    sleep(1); 
                }
            }
        } else {
            // Odoslanie emailu konkrétnemu príjemcovi
            Mail::to($request->recipient)->send(new UserNotificationEmail($request->subject, $request->content));
            $this->logEmail($request->recipient, $request->subject, $request->content);
        }
    
        return redirect()->back()->with('success', 'Email úspešne odoslaný používateľom.'); // Presmerovanie späť s úspešnou správou
    }
    
    // Metóda na zaznamenanie detailov emailu
    private function logEmail($recipient_email, $subject, $body)
    {
        EmailLog::create([
            'recipient_email' => $recipient_email,
            'subject' => $subject,
            'body' => $body,
        ]);
    }
    
}
