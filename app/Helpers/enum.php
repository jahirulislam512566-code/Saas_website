<?php

use App\Enums\OrderStatus;

// Helpers/enum.php

if (!function_exists('enum_options')) {
    function enum_options($enumClass): array
    {
        if (!method_exists($enumClass, 'options')) {
            return [];
        }
        return $enumClass::options();
    }
}

if (!function_exists('enum_values')) {
    function enum_values($enumClass): array
    {
        if (!method_exists($enumClass, 'values')) {
            return [];
        }
        return $enumClass::values();
    }
}

if (!function_exists('enum_labels')) {
    function enum_labels($enumClass): array
    {
        if (!method_exists($enumClass, 'labels')) {
            return [];
        }
        return $enumClass::labels();
    }
}

// Usage
$statusOptions = enum_options(OrderStatus::class);
$statusValues = enum_values(OrderStatus::class);
$statusLabels = enum_labels(OrderStatus::class);