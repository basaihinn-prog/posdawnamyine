<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'day',
        'slot_type',
        'start_time',
        'end_time',
        'time_difference',
        'is_available',
    ];


    protected $casts = [
        'is_available' => 'boolean',
    ];
}
