<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'status',
        'sub_tax',
        'vat_on_sale',
        'business_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'rate' => 'double',
        'sub_tax' => 'json',
        'status' => 'boolean',
        'business_id' => 'integer',
        'vat_on_sale' => 'integer',
    ];
}
