<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'gateway_id',
        'currency_id',
        'name',
        'mode',
        'data',
        'image',
        'status',
        'charge',
        'is_manual',
        'namespace',
        'accept_img',
        'manual_data',
        'instructions',
        'phone_required',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

     protected $casts = [
        'data' => 'json',
        'manual_data' => 'json',
    ];
}
