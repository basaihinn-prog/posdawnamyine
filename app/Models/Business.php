<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// CustomDomainAddon Domain model is optional; avoid hard import to allow removal.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_subscribe_id',
        'business_category_id',
        'companyName',
        'slug',
        'address',
        'phoneNumber',
        'pictureUrl',
        'will_expire',
        'subscriptionDate',
        'remainingShopBalance',
        'shopOpeningBalance',
        'vat_name',
        'vat_no',
        'status'
    ];

    public function enrolled_plan()
    {
        return $this->belongsTo(PlanSubscribe::class, 'plan_subscribe_id');
    }


    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'business_id');
    }

    public function tax()
    {
        return $this->hasOne(Tax::class)->where('vat_on_sale', 1);
    }


    public function domain()
    {
        if (! moduleCheck('CustomDomainAddon')) {
            return null;
        }
        $domainClass = '\\Modules\\CustomDomainAddon\\App\\Models\\Domain';
        return $this->hasOne($domainClass, 'business_id');
    }

    public function publicQrUrl()
    {
        if (!moduleCheck('RestaurantOnlineStore')) {
            return null;
        }

        if (!moduleCheck('CustomDomainAddon')) {

            return route('onlineStore.menus', [
                'restaurant_slug' => $this->slug
            ], true);
        }

        $domain = $this->domain()
            ->where('is_verified', 1)
            ->where('is_ssl_enabled', 1)
            ->where('status', 1)
            ->first();

        if ($domain) {
            return "https://{$domain->domain}/restaurant/{$this->slug}/menu";
        }

        return route('onlineStore.menus', [
            'restaurant_slug' => $this->slug
        ], true);
    }




    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'remainingShopBalance' => 'double',
        'shopOpeningBalance' => 'double',
        'plan_subscribe_id' => 'integer',
        'status' => 'integer',
        'business_category_id' => 'integer',
    ];
}
