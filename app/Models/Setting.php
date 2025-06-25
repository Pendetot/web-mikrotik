<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    protected $casts = [
        'value' => 'string',
    ];

    const CACHE_PREFIX = 'setting_';
    const CACHE_DURATION = 3600; // 1 hour

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $cacheKey = self::CACHE_PREFIX . $key;
        
        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            
            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        // Convert value to string for storage
        $stringValue = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;
        
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $stringValue,
                'type' => $type,
                'description' => $description
            ]
        );

        // Clear cache
        Cache::forget(self::CACHE_PREFIX . $key);

        return true;
    }

    /**
     * Get multiple settings at once
     */
    public static function getMultiple(array $keys, array $defaults = [])
    {
        $result = [];
        
        foreach ($keys as $key) {
            $default = $defaults[$key] ?? null;
            $result[$key] = self::get($key, $default);
        }

        return $result;
    }

    /**
     * Set multiple settings at once
     */
    public static function setMultiple(array $settings)
    {
        foreach ($settings as $key => $data) {
            if (is_array($data) && count($data) >= 2) {
                [$value, $type, $description] = array_pad($data, 3, null);
                self::set($key, $value, $type, $description);
            } else {
                self::set($key, $data);
            }
        }
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $settings = self::all();
        
        foreach ($settings as $setting) {
            Cache::forget(self::CACHE_PREFIX . $setting->key);
        }

        return true;
    }

    /**
     * Cast value to appropriate type
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            
            case 'integer':
                return (int) $value;
            
            case 'float':
                return (float) $value;
            
            case 'json':
            case 'array':
                return json_decode($value, true);
            
            case 'object':
                return json_decode($value);
            
            default:
                return $value;
        }
    }

    /**
     * Get all settings grouped by category
     */
    public static function getAllGrouped()
    {
        $settings = self::all();
        $grouped = [];

        foreach ($settings as $setting) {
            $parts = explode('_', $setting->key, 2);
            $category = $parts[0];
            $key = $parts[1] ?? $setting->key;
            
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            
            $grouped[$category][$setting->key] = self::castValue($setting->value, $setting->type);
        }

        return $grouped;
    }

    /**
     * Delete a setting
     */
    public static function forget($key)
    {
        self::where('key', $key)->delete();
        Cache::forget(self::CACHE_PREFIX . $key);
        
        return true;
    }

    /**
     * Check if setting exists
     */
    public static function has($key)
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Get settings by prefix
     */
    public static function getByPrefix($prefix)
    {
        $settings = self::where('key', 'like', $prefix . '%')->get();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = self::castValue($setting->value, $setting->type);
        }

        return $result;
    }
}