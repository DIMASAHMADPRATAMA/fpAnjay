<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // ✅ Tambahkan ini
use Midtrans\Snap;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric',
            'metode' => 'required|string'
        ]);

        try {
            $user = $request->user();
            $orderId = uniqid('ORDER-');

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $request->total,
                ],
                'customer_details' => [
                    'first_name' => $user->name ?? 'Guest',
                    'email' => $user->email ?? 'guest@example.com',
                    'phone' => $user->phone ?? '081234567890'
                ],
                'enabled_payments' => ['gopay', 'bank_transfer', 'shopeepay', 'indomaret'],
                'callbacks' => [
                    'finish' => url('/payment/finish')
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'token' => $snapToken,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage()); // ✅ sekarang tidak error lagi
            return response()->json([
                'error' => 'Gagal membuat token Midtrans',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
