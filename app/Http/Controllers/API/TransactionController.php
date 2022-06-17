<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Payment;
use DB;

class TransactionController extends Controller
{
    //
    function getCart(Request $request){
        $transaction = DB::table('transaction')->join('date_tiket', 'date_tiket.id', '=', 'transaction.ticket_date_id')
            ->join('tiket', 'tiket.id', '=', 'date_tiket.tiket_id')->where('transaction.auth_id', $request->user()->id)
            ->where('paid', false)->select('transaction.id as id', 'tiket.name', 'date_tiket.date_ticket as jadwal_tiket', 'transaction.created_at', 'transaction.updated_at', 'transaction.qty', 'date_tiket.price' )->first();
        
        return response()->json(
            [
                'status' => 'success',
                'data' => $transaction
            ]
        );
    }

    function postAddCart(Request $request, $date_ticket_id){
        $date_ticket = DB::table('date_tiket')->where('id', $date_ticket_id)->first();
        if ($date_ticket != null) {
            # code...
            $price = $request->qty * $date_ticket->price;
        }else{
            return response()->json(['status'=>'error', 'message'=> 'Schedule Tidak Ditemukan'], 401);
        }
        

        // $payment = Payment::create([
        //     'via' => $request->via,
        //     'status' => 'waiting',
        //     'amount' => $price
        // ]);
        $count = Transaction::where('auth_id',$request->user()->id)->where('paid', false)->count();
        if ($count< 1) {
            $create = Transaction::create([
                'auth_id' => $request->user()->id,
                'ticket_date_id' => $date_ticket_id,
                'payment_id' => 0,
                'paid' => false,
                'qty' => $request->qty,
                'order_num' => "BYR-PYT-".$date_ticket->id."-".now()->getTimestampMs()
            ]);
        }else{
            $create = null;
            return response()->json(['status'=>'error', 'message'=> 'Kamu belum checkout cart yang sebelumnya!'], 401);
        }
        

        return response()->json(
            [
                'status' => 'success',
                'data' => $create
            ]
        );
    }

    function deleteCart(Request $request, $id){
        $delete = DB::table('transaction')->where('id', $id)->where('paid', false)->delete();
        if ($delete == 0) {
            # code...
            return response()->json(
                [
                    'status' => 'success',
                    'message' => "Cart tidak ditemukan"
                ], 401
            );
        }else{
            return response()->json(
                [
                    'status' => 'success',
                    'message' => "Berhasil Menghapus Cart"
                ]
            );
        }
        
    }
    function getHistoryCart(Request $request){
        $history = DB::table('transaction')->join('date_tiket', 'date_tiket.id', '=', 'transaction.ticket_date_id')
            ->join('tiket', 'tiket.id', '=', 'date_tiket.tiket_id')
            ->join('payment', 'payment.id', '=', 'transaction.payment_id')
            ->where('transaction.auth_id', $request->user()->id)
            ->where('transaction.paid', true)
            ->select('transaction.id as id', 
                    'tiket.name', 
                    'date_tiket.date_ticket as jadwal_tiket', 
                    'payment.via',
                    'payment.no_rek',
                    'paid', 
                    'payment.status as status_payment',
                    'transaction.created_at', 
                    'transaction.updated_at', 
                    'transaction.qty', 
                    'date_tiket.price' )
            ->get();
        return response()->json(['status' => "success", 'data' => $history]);
    }
}
