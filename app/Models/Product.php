<?php

namespace App\Models;

use App\Models\Modifier;
use App\Models\Scopes\DataManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'productName',
        'business_id',
        'user_id',
        'category_id',
        'menu_id',
        'status',
        'preparation_time',
        'food_type',
        'price_type',
        'sales_price',
        'images',
        'description',
        'meta',
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DataManager('products.view-all-data'));
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class, 'product_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function modifiers()
    {
        return $this->hasMany(Modifier::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    protected $casts = [
        'meta' => 'json',
        'images' => 'json',
        'business_id' => 'integer',
        'category_id' => 'integer',
        'menu_id' => 'integer',
        'sales_price' => 'double',
    ];
}
