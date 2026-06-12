<?php

namespace App\Models;

use App\Models\Scopes\DataManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Party extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'phone',
        'due',
        'image',
        'status',
        'address',
        'business_id',
        'user_id',
        'opening_balance',
        'notes',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DataManager('parties.view-all-data'));
    }

    public function sales() : HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function purchases() : HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function delivery_addresses() : HasMany
    {
        return $this->hasMany(DeliveryAddress::class);
    }

    public function sales_dues() : HasMany
    {
        return $this->hasMany(Sale::class)->where('dueAmount', '>', 0);
    }

    public function purchases_dues() : HasMany
    {
        return $this->hasMany(Purchase::class)->where('dueAmount', '>', 0);
    }

    public function dueCollect()
    {
        return $this->hasOne(DueCollect::class);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
        'business_id' => 'integer',
        'due' => 'double',
        'opening_balance' => 'double',
    ];
}
