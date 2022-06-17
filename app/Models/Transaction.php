<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transaction';
    protected $fillable = ['auth_id', 'ticket_date_id', 'payment_id', 'paid', 'qty', 'order_num'];
}
