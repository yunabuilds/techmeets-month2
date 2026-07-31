<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
   protected $fillable = [
        'name',
        'description',
        'date',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
