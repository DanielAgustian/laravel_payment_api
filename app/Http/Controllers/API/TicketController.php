<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class TicketController extends Controller
{
    //
    function getTicket(Request $request){
        $data = DB::table('tiket')->get();
        return response()->json([
            'status' => "Success",
            'data' => $data
        ]);
    }
    function getTicketSchedule($id){
        $data = DB::table('date_tiket')->where('tiket_id', $id)->get();
        return response()->json([
            'status' => "Success",
            'data' => $data
        ]);
    }
}
