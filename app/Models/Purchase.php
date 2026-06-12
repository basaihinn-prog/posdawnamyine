<?php

namespace App\Models;

use App\Models\Scopes\DataManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'party_id',
        'business_id',
        'user_id',
        'payment_type_id',
        "discountAmount",
        "discountPercentage",
        "discount_type",
        "tax_amount",
        "tax_percentage",
        "dueAmount",
        "paidAmount",
        "totalAmount",
        "invoiceNumber",
        "purchaseDate",
        "purchase_data",
        "note",
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DataManager('purchases.view-all-data'));
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetails::class);
    }

    public function party() : BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function business() : BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function payment_type() : BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $id = Purchase::where('business_id', auth()->user()?->business_id ?? 1)->count() + 1;
            $model->invoiceNumber = $id;
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'invoiceNumber' => 'string',
        'discountAmount' => 'double',
        'discountPercentage' => 'double',
        'tax_amount' => 'double',
        'tax_percentage' => 'double',
        'dueAmount' => 'double',
        'paidAmount' => 'double',
        'totalAmount' => 'double',
        'party_id' => 'integer',
        'business_id' => 'integer',
        'user_id' => 'integer',
        'payment_type_id' => 'integer',
        'purchase_data' => 'json',
    ];
}
