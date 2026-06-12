<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'name',
        'phone',
        'address',
    ];

    protected $casts = [
        'party_id' => 'integer',
    ];
}
