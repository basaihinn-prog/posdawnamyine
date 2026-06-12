<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingAddress extends Model
{
    use HasFactory;


    protected $fillable = [
        'business_id',
        'user_id',
        'name',
        'phone',
        'email',
        'address',
        'city',
        'postcode',
    ];

    protected $casts = [
        'business_id' => 'integer',
        'user_id' => 'integer',
    ];
}
