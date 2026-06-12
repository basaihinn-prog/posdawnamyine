<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'name',
        'phone',
        'email',
        'date',
        'guest',
        'type',
        'time',
        'description',
        'status',
    ];

    public function tables()
    {
        return $this->belongsToMany(Table::class, 'reservation_tables');
    }
}
