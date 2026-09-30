<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use App\Models\Purchase;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
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

        } catch (ApiErrorException $e) {
            Log::error('Stripe決済セッション作成エラー: ' . $e->getMessage());
            return back()->withErrors(['payment' => '決済処理に失敗しました。時間をおいて再度お試しください。']);
        }
    }

    public function success(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    $sessionId = $request->query('session_id');

    try {
    $session = Session::retrieve($sessionId);
 } catch (ApiErrorException $e) {
            Log::error('Stripeセッション取得エラー: ' . $e->getMessage());
            return redirect()->route('checkout.cancel')
                ->withErrors(['payment' => '決済情報の確認に失敗しました。']);
        }

        if ($session->payment_status !== 'paid') {
            return redirect()->route('checkout.cancel')
                ->withErrors(['payment' => 'お支払いが完了していません。']);
    }

     Purchase::firstOrCreate(
        ['stripe_session_id' => $session->id],
        [
            'product_name' => '商品名',
            'amount'       => $session->amount_total,
            'email'        => $session->customer_details->email ?? null,
        ]
    );


    return view('checkout.success');
}
}