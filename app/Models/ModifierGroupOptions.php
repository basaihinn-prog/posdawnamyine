<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModifierGroupOptions extends Model
{
    use HasFactory;

    protected $fillable = [
        'modifier_group_id',
        'name',
        'price',
        'is_available',
    ];

    public function modifier_group() : BelongsTo
    {
        return $this->belongsTo(ModifierGroups::class, 'modifier_group_id');
    }

    protected $casts = [
        'modifier_group_id' => 'integer',
        'price' => 'double',
        'is_available' => 'integer',
    ];
}
