<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'product_id',
        'modifier_group_id',
        'is_required',
        'is_multiple',
    ];

    public function modifier_group() {

        return $this->belongsTo(ModifierGroups::class);
    }

    public function product() {

        return $this->belongsTo(Product::class);
    }

    protected $casts = [
        'business_id' => 'integer',
        'product_id' => 'integer',
        'modifier_group_id' => 'integer',
        'is_required' => 'integer',
        'is_multiple' => 'integer',
    ];
}
