<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Purchase;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'jpy',
                    'product_data' => ['name' => '商品名'],
                    'unit_amount'  => 1000,  // 1,000円
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('checkout.cancel'),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    $sessionId = $request->query('session_id');
    $session = Session::retrieve($sessionId);

    Purchase::create([
        'stripe_session_id' => $session->id,
        'product_name'      => '商品名',
        'amount'            => $session->amount_total,
        'email'             => $session->customer_details->email ?? null,
    ]);

    return view('checkout.success');
}
}