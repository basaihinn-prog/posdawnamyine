<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KotTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'table_id',
        'cancel_reason_id',
        'kotNumber',
        'bill_no',
        'status',
        'notes',
        'total_amount',
    ];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function cancel_reason()
    {
        return $this->belongsTo(CancelReason::class);
    }

    public function sale()
    {
        return $this->hasOne(Sale::class, 'kot_id', 'id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->business_id) {
                throw new \Exception("Business ID is required to generate kot number.");
            }
            $id = KotTicket::where('business_id', $model->business_id)->count() + 1;
            $model->kot_Number = $id;
        });
    }

    protected $casts = [
        'business_id' => 'integer',
        'table_id' => 'integer',
        'total_amount' => 'double',
        'items' => 'json',
    ];
}
