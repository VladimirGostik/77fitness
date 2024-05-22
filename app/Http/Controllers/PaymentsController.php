<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Client;
use App\Models\User;
use App\Models\ChargingCredit;
use App\Services\GoPayService;


class PaymentsController extends Controller
{
    protected $goPayService;

    public function __construct(GoPayService $goPayService)
    {
        $this->goPayService = $goPayService;
    }


    public function index(Request $request)
{
    // Your charge logic here
    return view('credit.charge_credit');
}


    public function charge(Request $request)
    {
        $amount = $request->input('amount');

        // Validation: Check minimum charge amount
        if ($amount < 1) {
            return back()->withErrors(['amount' => 'Minimálna suma na dobitie je 1 €.']);
        }

        // Get the current client's ID
        $clientId = auth()->user()->client->user_id;

        // Create GoPay API object using your credentials
        $order = [
            'first_name' => auth()->user()->first_name,
            'last_name' => auth()->user()->last_name,
            'email' => auth()->user()->email,
            'phone' => auth()->user()->phone_number,
            'amount' => $amount,
            'order_number' => uniqid(),
            'description' => 'Dobitie kreditu',
            'items' => [
                [
                    'name' => 'Dobitie kreditu',
                    'amount' => $amount * 100,
                ]
            ],
        ];

        // Create the GoPay payment request
        $response = $this->goPayService->createPayment($order);

        // Create a new ChargingCredit record
        ChargingCredit::create([
            'external_transaction_id' => $response->json['id'], // Replace with actual transaction ID
            'client_id' => $clientId,
            'amount' => $amount,
            'currency' => 'EUR', // Based on payment request
            'payment_method' => $request->input('payment_method'), // Assuming payment method from the form
            'payment_status' => $payment->state, // Initial payment state from GoPay
            // ... (Add other relevant details)
        ]);

        // Redirect to GoPay payment gateway
        return redirect($payment->url);
    }

    public function callback(Request $request)
    {
        $gopay = new Api([
            'clientId' => env('GOPAY_CLIENT_ID'),
            'secretKey' => env('GOPAY_SECRET_KEY'),
        ]);

        // Retrieve the payment ID from the request
        $paymentId = $request->input('id');

        // Verify the payment status in GoPay
        $payment = $gopay->getPayment($paymentId);
        if ($payment->status === 'PAID') {
            // Successful payment processing
            $amount = $payment->amount;

            // Get the client's ID from the session or database (assuming client authentication)
            $clientId = Auth::guard('client')->user()->id;

            // Update the client's credit balance
            $client = Client::find($clientId);
            $client->credit += $amount;
            $client->save();

            // Redirect to a success page
            return redirect('/credit/success');
        } else {
            // Failed payment handling
            return redirect('/credit/error');
        }
    }

    public function chargeCredit(Request $request)
    {
        // Your logic to display the user selection and credit management form
        $clients = Client::all(); // Assuming you want to display a list of users
        //dd($clients);
        return view('credit.charge_creditAdmin', compact('clients'));
    }


    public function chargeAdmin(Request $request)
        {
            $user_id = $request->input('user_id');
            $admin_id = $request->input('admin_id');
            $amount = $request->input('amount');
        
            // Validation (same as before)
        
            // Update user's credit
            $client = Client::find($user_id);
            $client->increment('credit', $amount);
        
            // Save additional charge details (using the provided values)
            $charge = new ChargingCredit([
                'client_id' => $client->user_id,
                'admin_id' => $admin_id,
                'amount' => $amount,
                'currency' => 'EUR',
                'payment_method' => 'cash',
                'payment_status' => 'PAID',
            ]);
            $charge->save();
        
            // Log the action (same as before)
        
            return back()->withSuccess("Kredit úspešne nabitý.");
        }
        
}
