<?php

namespace App\Models;

use App\Models\Scopes\DataManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
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
        'address_id',
        'user_id',
        'tax_id',
        'kot_id',
        'table_id',
        'staff_id',
        'coupon_id',
        'payment_type_id',
        'billing_address_id',
        'business_gateway_id',
        'discountAmount',
        'discountPercentage',
        'coupon_amount',
        'coupon_percentage',
        'discount_type',
        'isPaid',
        "tax_amount",
        "dueAmount",
        'paidAmount',
        'totalAmount',
        'invoiceNumber',
        'saleDate',
        'sale_data',
        'sales_type',
        'status',
        'order_status',
        'meta',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DataManager('sales.view-all-data'));
    }

    public function business() : BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function details()
    {
        return $this->hasMany(SaleDetails::class);
    }

    public function tax() : BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function party() : BelongsTo
    {
        return $this->belongsTo(Party::class);
    }

    public function payment_type() : BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon() : BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function delivery_address() : BelongsTo
    {
        return $this->belongsTo(DeliveryAddress::class, 'address_id');
    }

    public function kot_ticket() : BelongsTo
    {
        return $this->belongsTo(KotTicket::class, 'kot_id');
    }

    public function table() : BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function dueCollect()
    {
        return $this->hasOne(DueCollect::class);
    }

    public function business_gateway()
    {
        return $this->belongsTo(BusinessGateway::class);
    }

    public function getMetaAttribute($value)
    {
        $meta = json_decode($value, true);

        // Cast specific fields to desired types
        if (isset($meta['tip'])) {
            $meta['tip'] = (float) $meta['tip'];
        }

        if (isset($meta['delivery_charge'])) {
            $meta['delivery_charge'] = (float) $meta['delivery_charge'];
        }

        return $meta;
    }

    protected $casts = [
        'discountAmount' => 'double',
        'discountPercentage' => 'double',
        'coupon_amount' => 'double',
        'coupon_percentage' => 'double',
        'isPaid' => 'boolean',
        'tax_amount' => 'double',
        'paidAmount' => 'double',
        'totalAmount' => 'double',
        'dueAmount' => 'double',
        'meta' => 'json',
        'sale_data' => 'json',
        'tax_id' => 'integer',
        'business_id' => 'integer',
        'party_id' => 'integer',
        'address_id' => 'integer',
        'user_id' => 'integer',
        'kot_id' => 'integer',
        'table_id' => 'integer',
        'staff_id' => 'integer',
        'coupon_id' => 'integer',
        'payment_type_id' => 'integer',
        'business_gateway_id' => 'integer',
    ];
}
