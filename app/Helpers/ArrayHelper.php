<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class ArrayHelper
{
    /**
     * Get array value using dot notation
     */
    public static function get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }

    /**
     * Set array value using dot notation
     */
    public static function set(&$array, $key, $value): void
    {
        Arr::set($array, $key, $value);
    }

    /**
     * Remove keys from array
     */
    public static function except($array, $keys): array
    {
        return Arr::except($array, $keys);
    }

    /**
     * Get only specified keys from array
     */
    public static function only($array, $keys): array
    {
        return Arr::only($array, $keys);
    }

    /**
     * Check if array has key
     */
    public static function has($array, $key): bool
    {
        return Arr::has($array, $key);
    }

    /**
     * Flatten array
     */
    public static function flatten($array, $depth = INF): array
    {
        return Arr::flatten($array, $depth);
    }

    /**
     * Pluck values from array
     */
    public static function pluck($array, $value, $key = null): array
    {
        return Arr::pluck($array, $value, $key);
    }

    /**
     * Sort array by key
     */
    public static function sortBy($array, $key): array
    {
        $sorted = $array;
        usort($sorted, function ($a, $b) use ($key) {
            return $a[$key] <=> $b[$key];
        });

        return $sorted;
    }

    /**
     * Sort array by key descending
     */
    public static function sortByDesc($array, $key): array
    {
        $sorted = $array;
        usort($sorted, function ($a, $b) use ($key) {
            return $b[$key] <=> $a[$key];
        });

        return $sorted;
    }

    /**
     * Group array by key
     */
    public static function groupBy($array, $key): array
    {
        $grouped = [];

        foreach ($array as $item) {
            $value = $item[$key] ?? null;
            $grouped[$value][] = $item;
        }

        return $grouped;
    }

    /**
     * Convert to array
     */
    public static function toArray($data): array
    {
        if (is_array($data)) {
            return $data;
        }

        if (method_exists($data, 'toArray')) {
            return $data->toArray();
        }

        if (is_object($data)) {
            return (array) $data;
        }

        return [];
    }

    /**
     * Remove empty values
     */
    public static function removeEmpty($array): array
    {
        return array_filter($array, function ($value) {
            return !is_null($value) && $value !== '' && $value !== [];
        });
    }

    /**
     * Recursive array merge
     */
    public static function mergeRecursive(array $array1, array $array2): array
    {
        return array_merge_recursive($array1, $array2);
    }

    /**
     * Get unique values
     */
    public static function unique($array): array
    {
        return array_unique($array);
    }

    /**
     * Chunk array
     */
    public static function chunk($array, $size): array
    {
        return array_chunk($array, $size);
    }
}