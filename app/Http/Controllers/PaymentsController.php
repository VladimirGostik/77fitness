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
    protected $goPay;

    // public function __construct()
    // {
    //     $this->goPay = GoPay::payments([
    //         'goid' => env('GOPAY_GOID'),
    //         'clientId' => env('GOPAY_CLIENT_ID'),
    //         'clientSecret' => env('GOPAY_CLIENT_SECRET'),
    //         'isProductionMode' => true,
    //         'scope' => GoPay::FULL,
    //         'language' => Language::SLOVAK,
    //     ]);
    // }


    public function index(Request $request)
{
    // Your charge logic here
    return view('credit.charge_credit');
}


public function charge(Request $request)
{
    $amount = $request->input('amount');
    // Validácia: Skontrolujte minimálnu sumu dobíjania
    if ($amount < 1) {
        return back()->withErrors(['amount' => 'Minimálna suma na dobitie je 1 €.']);
    }
    // Získajte aktuálne ID klienta
    $clientId = auth()->user()->client->user_id;
    
    // Pripravte údaje o platbe pre GoPay
    $order = [
        'payer' => [
            'default_payment_instrument' => PaymentInstrument::BANK_ACCOUNT,
            'allowed_payment_instruments' => [PaymentInstrument::BANK_ACCOUNT],
            'contact' => [
                'first_name' => auth()->user()->first_name,
                'last_name' => auth()->user()->last_name,
                'email' => auth()->user()->email,
                'phone_number' => auth()->user()->phone_number,
            ],
        ],
        'amount' => $amount * 100, // Prevod na centy
        'currency' => 'EUR',
        'order_number' => uniqid(),
        'order_description' => 'Dobitie kreditu',
        'items' => [
            [
                'name' => 'Dobitie kreditu',
                'amount' => $amount * 100, // Prevod na centy
                'type' => PaymentItemType::ITEM,
            ]
        ],
        'callback' => [
            'return_url' => route('payments.success'),
            'notification_url' => route('payments.notify'),
        ],
    ];

    try {
        // Vytvorenie platobnej požiadavky na GoPay
        $response = $this->goPay->createPayment($order);

        // Logovanie odpovede pre debugovanie
        \Log::info('GoPay response:', (array) $response);

        // Skontrolujte, či odpoveď obsahuje potrebné údaje
        if (!isset($response->json['id']) || !isset($response->json['state']) || !isset($response->json['gw_url'])) {
            return back()->withErrors(['error' => 'Nesprávna odpoveď od GoPay API.']);
        }

        $paymentId = $response->json['id'];
        $paymentState = $response->json['state'];
        $paymentUrl = $response->json['gw_url'];

        // Vytvorenie nového záznamu ChargingCredit
        ChargingCredit::create([
            'external_transaction_id' => $paymentId,
            'client_id' => $clientId,
            'amount' => $amount,
            'currency' => 'EUR',
            'payment_method' => 'GoPay',
            'payment_status' => $paymentState,
        ]);

        // Presmerovanie na GoPay platobnú bránu
        return redirect($paymentUrl);
    } catch (\Exception $e) {
        \Log::error('Error creating GoPay payment:', ['error' => $e->getMessage()]);
        return back()->withErrors(['error' => 'Vyskytla sa chyba pri vytváraní platby. Skúste to znova.']);
    }
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
