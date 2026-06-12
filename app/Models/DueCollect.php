<?php

namespace App\Models;

use App\Models\Scopes\DataManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DueCollect extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_id',
        'party_id',
        'user_id',
        'purchase_id',
        'sale_id',
        'payment_type_id',
        'invoiceNumber',
        'totalDue',
        'dueAmountAfterPay',
        'payDueAmount',
        'paymentType',
        'paymentDate',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DataManager('dueCollectionReport.view-all-data'));
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $id = DueCollect::where('business_id', auth()->user()->business_id)->count() + 1;
            $model->invoiceNumber = "#" . $id;
        });
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function party() : BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function sale() : BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function purchase() : BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function payment_type() : BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'business_id' => 'integer',
        'party_id' => 'integer',
        'sale_id' => 'integer',
        'user_id' => 'integer',
        'payment_type_id' => 'integer',
        'purchase_id' => 'integer',
        'totalAmount' => 'double',
        'totalDue' => 'double',
        'dueAmountAfterPay' => 'double',
        'payDueAmount' => 'double',
    ];
}
