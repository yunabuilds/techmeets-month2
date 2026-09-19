<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'stripe_session_id',
        'product_name',
        'amount',
        'email',
    ];
}