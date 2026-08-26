<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
    'title',
    'description',
    'due_date',
    'priority',
    'is_completed',
    'user_id',
];

// belongsTo: タスク(多) → ユーザー(1)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
