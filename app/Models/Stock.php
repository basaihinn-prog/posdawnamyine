<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'product_id',
        'productStock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'business_id' => 'integer',
        'product_id' => 'integer',
        'productStock' => 'integer',
    ];
}
