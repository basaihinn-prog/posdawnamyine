<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'area_id',
        'name',
        'capacity',
        'is_booked',
        'qr_code',
        'status'
    ];

    public function kot_ticket()
    {
        return $this->hasOne(KotTicket::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

     public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_tables');
    }

    protected $casts = [
        'area_id' => 'integer',
        'capacity' => 'integer',
        'business_id' => 'integer',
        'is_booked' => 'integer',
        'status' => 'integer',
    ];
}
