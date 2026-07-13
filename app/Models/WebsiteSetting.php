<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'website_id',
        'key',
        'value',
        'group',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    // Relationships
    public function website()
    {
        return $this->belongsTo(Website::class);
    }

    // Helper methods
    public static function get($websiteId, $key, $default = null)
    {
        $setting = static::where('website_id', $websiteId)
            ->where('key', $key)
            ->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($websiteId, $key, $value)
    {
        $setting = static::firstOrNew([
            'website_id' => $websiteId,
            'key' => $key,
        ]);
        $setting->value = $value;
        $setting->save();
        return $setting;
    }
}