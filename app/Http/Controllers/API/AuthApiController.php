<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\AuthModel;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    //
    function registerAPI(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 403);       
        }
        $count = AuthModel::where('email' , $request->email)->count();
        if ($count > 0) {
            # code...
            return response()->json(['status'=>'error', 'message'=> 'Email sudah terdaftar'], 401);
        }
        $user = AuthModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
         ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }
    function loginAPI(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 403);       
        }
        $count = AuthModel::where('email' , $request->email)->count();
        if ($count< 1) {
            return response()->json(['status'=>'error', 'message'=> 'Email belum terdaftar'], 401);

        }
        $data = AuthModel::where('email' , $request->email)->first();
       
        if (!Hash::check($request->password, $data->password)) {
            return response()->json(['status'=>'error', 'message'=> 'Password Salah'], 403);
        }
        $token = $data->createToken('auth_token')->plainTextToken;

        return response()
        ->json(['data' => $data,'access_token' => $token, 'token_type' => 'Bearer', ]);
    }
}
