<?php

namespace App\Models;

use App\Models\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'sale_id',
        'purchase_id',
        'payment_type_id',
        'invoiceNumber',
        'date',
        'total_amount',
        'paid_amount',
        'due_amount',
        'type',
    ];

    public function payment_type() {

        return $this->belongsTo(PaymentType::class);
    }

    protected $casts = [
        'payment_type_id' => 'integer',
        'total_amount' => 'double',
        'paid_amount' => 'double',
        'due_amount' => 'double',
        'business_id' => 'integer',
        'sale_id' => 'integer',
        'purchase_id' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
           if (!$model->business_id) {
            throw new \Exception("Business ID is required to generate invoice number.");
          }

            $id = Transaction::where('business_id', $model->business_id)->count() + 1;
            $model->invoiceNumber = "T-" . str_pad($id, 5, '0', STR_PAD_LEFT);
        });

    }
}
