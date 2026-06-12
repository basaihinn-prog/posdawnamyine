<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseDetails extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id',
        'ingredient_id',
        'unit_id',
        'unit_price',
        'quantities',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function ingredient() : BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
    public function unit() : BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'unit_price' => 'double',
        'purchase_id' => 'integer',
        'ingredient_id' => 'integer',
        'unit_id' => 'integer',
        'quantities' => 'integer',
    ];
}
