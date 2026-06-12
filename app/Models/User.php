<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id',
        'staff_id',
        'name',
        'role',
        'email',
        'phone',
        'gender',
        'image',
        'lang',
        'status',
        'password',
        'visibility',
        'fcm_token',
        'remember_token',
        'email_verified_at',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'shop-owner') return true;
        [$module, $action] = explode('.', $permission);
        return isset($this->visibility[$module][$action]) && $this->visibility[$module][$action] == "1";
    }

    public function hasAnyPermission(array $permissions)
    {
        if ($this->role === 'shop-owner') return true;

        $visibility = $this->visibility ?? [];
        foreach ($permissions as $permission) {
            [$module, $action] = explode('.', $permission);

            if (!empty($visibility[$module][$action]) && $visibility[$module][$action] == "1") {
                return true;
            }
        }

        return false;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'business_id' => 'integer',
        'staff_id' => 'integer',
        'visibility' => 'json',
        'fcm_token' => 'json',
        'email_verified_at' => 'datetime',
    ];

}
