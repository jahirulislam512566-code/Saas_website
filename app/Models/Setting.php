<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    // Scopes
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    // Helper methods
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        $setting = static::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->save();
        return $setting;
    }

    public function getValueAttribute($value)
    {
        if ($this->type === 'boolean') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        if ($this->type === 'json') {
            return json_decode($value, true);
        }
        return $value;
    }

    public function setValueAttribute($value)
    {
        if ($this->type === 'boolean') {
            $this->attributes['value'] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        } elseif ($this->type === 'json') {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
}