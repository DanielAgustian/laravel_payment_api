<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\TicketController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\PaymentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [AuthApiController::class, 'registerApi']);
Route::post('/login', [AuthApiController::class, 'loginApi']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::get('/profile', function(Request $request) {
    //     return auth()->user();
    // });
    Route::get('/get-tiket', [TicketController::class, 'getTicket'])->name('getTicketAPI');
    Route::get('/get-tiket-schedule/{id}', [TicketController::class, 'getTicketSchedule']);
    
    // Cart
    Route::post('/add-to-cart/{date_ticket_id}', [TransactionController::class, 'postAddCart']);
    Route::get('/get-cart', [TransactionController::class, 'getCart']);
    Route::post('/delete-cart/{id}',[TransactionController::class, 'deleteCart']);
    Route::post('/checkout-cart',[PaymentController::class, 'checkoutCart']);
    Route::get('/history-cart', [TransactionController::class, 'getHistoryCart']);
});
