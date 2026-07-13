<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait TenantAware
{
    protected function getTenantId()
    {
        $table = $this->getTableName();
        $hasTenantColumn = Schema::hasColumn($table, 'tenant_id');
        return $hasTenantColumn ? Auth::user()->tenant_id : null;
    }

    protected function hasTenantColumn()
    {
        return Schema::hasColumn($this->getTableName(), 'tenant_id');
    }

    protected function applyTenantFilter($query)
    {
        $table = $this->getTableName();
        if (Schema::hasColumn($table, 'tenant_id')) {
            $tenantId = Auth::user()->tenant_id;
            if ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }
        }
        return $query;
    }

    protected function authorizeTenant($model)
    {
        $table = $this->getTableName();
        if (Schema::hasColumn($table, 'tenant_id')) {
            $tenantId = Auth::user()->tenant_id;
            if ($tenantId && $model->tenant_id !== $tenantId) {
                abort(403, 'Unauthorized action.');
            }
        }
    }

    protected function getTableName()
    {
        // Get the model class name from the controller
        $modelClass = str_replace('Controller', '', class_basename($this));
        $modelClass = 'App\\Models\\' . $modelClass;
        if (class_exists($modelClass)) {
            return (new $modelClass)->getTable();
        }
        return strtolower($modelClass) . 's';
    }
}