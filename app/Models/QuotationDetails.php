<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'product_id',
        'variation_id',
        'price',
        'quantities',
        'instructions'
    ];

    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation() : BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function detail_options() : HasMany
    {
        return $this->hasMany(QuotationDetailOption::class, 'quotation_detail_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'quotation_id' => 'integer',
        'product_id' => 'integer',
        'variation_id' => 'integer',
        'price' => 'double',
        'quantities' => 'integer',
    ];
}
