<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\MidtransConfig;
use Midtrans\Snap;

class MidtransController extends Controller
{
    public function createTransaction(Request $request)
    {
        MidtransConfig::init();

        $orderId = uniqid('ORDER-');
        $total = $request->total ?? 10000;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $total,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'User',
                'email' => auth()->user()->email ?? 'user@example.com',
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'token' => $snapToken,
                'order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Midtrans error: ' . $e->getMessage(),
            ], 500);
        }
    }

    
}
