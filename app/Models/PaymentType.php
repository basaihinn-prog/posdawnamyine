<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'is_view',
        'status',
    ];

    protected $casts =[
        'business_id' => 'integer',
        'is_view' => 'integer',
        'status' => 'integer',
    ];
}
