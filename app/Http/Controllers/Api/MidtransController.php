<?php

namespace App\Http\Controllers\Api;

use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class MidtransController extends Controller
{
    public function callback()
    {
        // Set configuration midtrans
        Config::$serverKey = config('services.midtrans.serverKey');        
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
        
        // Make instance midtrans notification
        $notification = new Notification;
        
        // Assign to variable
        $status = $notification->transaction_status;
        $type = $notification->payment_type;
        $fraud = $notification->fraud_status;
        $order_id = $notification->order_id;

        // Get transaction id
        $order = explode('-', $order_id); //['LUX',1]

        // Find transaction by id
        $transaction = Transaction::findOrFail($order[1]);

        // Handle notification status midtrans
        if ($status == 'capture'){
            if ($type == 'credit_card'){
                if ($fraud == 'challenge'){
                    $transaction->status = 'PENDING';
                } else {
                    $transaction->status = 'SUCCESS';
                }
            }
        }
        else if ($status == 'settlement'){
            $transaction->status = 'SUCCESS';
        }
        else if ($status == 'pending'){
            $transaction->status = 'PENDING';
        }
        else if ($status == 'deny'){
            $transaction->status = 'PENDING';
        }
        else if ($status == 'expire'){
            $transaction->status = 'CANCELED';
        }
        else if ($status == 'cancel'){
            $transaction->status = 'CANCELED';
        }

        // Save transaction
        $transaction->save();

        // Return response for midtrans
        return response()->json([
            'meta' => [
                'code' => 200,
                'message' => 'Midtrans Notification Success!'
            ]
        ]);
    }
}
