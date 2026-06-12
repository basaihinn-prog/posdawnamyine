<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleDetailOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_detail_id',
        'option_id',
        'modifier_id'
    ];

    public function modifier_group_option() : BelongsTo
    {
        return $this->belongsTo(ModifierGroupOptions::class, 'option_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'sale_detail_id' => 'integer',
        'option_id' => 'integer',
        'modifier_id' => 'integer',
    ];
}
