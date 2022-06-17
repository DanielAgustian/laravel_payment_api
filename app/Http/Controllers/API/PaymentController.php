<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Payment;
use DB;
class PaymentController extends Controller
{
    //
    function checkoutCart(Request $request){
        $count = Transaction::where('auth_id', $request->user()->id)
            ->where('paid', false)
            ->count();
        
        if ($count < 1) {
            return response()->json(
                [
                    'status'=> 'error',
                    'message' => 'Cart Tidak Ditemukan' 
                ], 401
            );
        }

        if (!$request->via) {
            return response()->json(
                [
                    'status'=> 'error',
                    'message' => 'Metode Pembayaran Tidak Boleh Kosong' 
                ], 401
            );
        }
        $transaction = Transaction::where('auth_id', $request->user()->id)
                ->where('paid', false)->first();
        $date_ticket = DB::table('date_tiket')->where('id', $transaction->ticket_date_id)->first();
        $price = $transaction->qty * $date_ticket->price;
        $no_rek = rand(1111111,9999999);
        $payment = Payment::create(['via'=> $request->via, 
                                    'auth_id' =>$request->user()->id , 
                                    'status' => 'waiting', 
                                    'amount' => $price, 
                                    'no_rek' => $no_rek ]);

        $result = Transaction::where('auth_id', $request->user()->id)
                ->where('paid', false)
                ->update([
                    'paid' => true,
                    'payment_id' => $payment->id
                ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Sukses Checkout Cart!',
            'data' => $result
            
        ]);
    }
 
}
