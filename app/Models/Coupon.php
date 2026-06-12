<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'business_id',
        'image',
        'code',
        'start_date',
        'end_date',
        'discount_type',
        'discount',
        'description',
    ];

    protected $casts = [
        'business_id' => 'integer',
        'discount' => 'double',
    ];
}
