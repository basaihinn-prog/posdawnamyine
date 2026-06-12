<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifierGroups extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
    ];

    public function modifiers() {
        return $this->hasMany(Modifier::class, 'modifier_group_id');
    }

    public function modifier_group_option() {
        return $this->hasMany(ModifierGroupOptions::class, 'modifier_group_id', 'id');
    }

    protected $casts = [
        'business_id' => 'integer',
        'total_modifier' => 'integer',
        'options' => 'json',
    ];
}
