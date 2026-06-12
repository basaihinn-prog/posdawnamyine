<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
    ];

    public function items()
    {
        return $this->hasMany(Product::class);
    }

    protected $casts = [
        'business_id' => 'integer',
    ];
}
