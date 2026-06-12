<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'is_popular',
        'duration',
        'offerPrice',
        'subscriptionName',
        'subscriptionPrice',
        'addon_domain_limit',
        'subdomain_limit',
        'features',
        'icon'
    ];

    public function planSubscribes()
    {
        return $this->hasMany(PlanSubscribe::class, 'plan_id');
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'duration' => 'integer',
        'offerPrice' => 'double',
        'subscriptionPrice' => 'double',
        'status' => 'integer',
        'is_popular' => 'integer',
        'addon_domain_limit' => 'integer',
        'subdomain_limit' => 'integer',
        'features' => 'json',
    ];
}
