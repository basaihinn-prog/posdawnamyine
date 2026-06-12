<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class DataManager implements Scope
{
    protected $fieldName;
    protected $permissionName;

    public function __construct(string $permissionName, string $fieldName = 'user_id')
    {
        $this->fieldName = $fieldName;
        $this->permissionName = $permissionName;
    }

    public function apply(Builder $builder, Model $model)
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Check static permission name
        if (!$user->hasPermission($this->permissionName) && $user->role != 'shop-owner') {
            $builder->where($model->getTable() . '.' . $this->fieldName, $user->id);
        }
    }
}
