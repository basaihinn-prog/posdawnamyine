<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'star',
        'client_name',
        'client_image',
        'work_at',
        'text'
    ];

    protected $casts = [
        'business_id' => 'integer',
        'star' => 'integer',
    ];
}
