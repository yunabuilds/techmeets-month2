<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'email',
        'number_of_people',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
