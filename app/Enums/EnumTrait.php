<?php

namespace App\Enums;

use ReflectionClass;
use InvalidArgumentException;

trait EnumTrait
{
    /**
     * Get all enum values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all enum labels
     */
    public static function labels(): array
    {
        $cases = self::cases();
        $labels = [];
        
        foreach ($cases as $case) {
            $labels[] = $case->label();
        }
        
        return $labels;
    }

    /**
     * Get all enum values with labels
     */
    public static function options(): array
    {
        $cases = self::cases();
        $options = [];
        
        foreach ($cases as $case) {
            $options[$case->value] = $case->label();
        }
        
        return $options;
    }

    /**
     * Get enum from value
     */
    public static function fromValue(string $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new InvalidArgumentException("Invalid value '{$value}' for enum " . static::class);
    }

    /**
     * Check if value is valid
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }

    /**
     * Get random enum case
     */
    public static function random(): self
    {
        $cases = self::cases();
        return $cases[array_rand($cases)];
    }

    /**
     * Get enum label
     */
    public function label(): string
    {
        return ucwords(str_replace('_', ' ', strtolower($this->value)));
    }

    /**
     * Get enum description
     */
    public function description(): string
    {
        $descriptions = $this->descriptions();
        return $descriptions[$this->value] ?? $this->label();
    }

    /**
     * Get enum color
     */
    public function color(): string
    {
        $colors = $this->colors();
        return $colors[$this->value] ?? '#6c757d';
    }

    /**
     * Get enum icon
     */
    public function icon(): string
    {
        $icons = $this->icons();
        return $icons[$this->value] ?? 'fa-circle';
    }

    /**
     * Check if enum matches value
     */
    public function equals(string $value): bool
    {
        return $this->value === $value;
    }

    /**
     * Check if enum is in array
     */
    public function in(array $values): bool
    {
        return in_array($this->value, $values, true);
    }

    /**
     * Get all cases as array
     */
    public static function toArray(): array
    {
        return array_map(function ($case) {
            return [
                'value' => $case->value,
                'label' => $case->label(),
                'description' => $case->description(),
                'color' => $case->color(),
                'icon' => $case->icon(),
            ];
        }, self::cases());
    }

    /**
     * Get descriptions mapping
     */
    protected static function descriptions(): array
    {
        return [];
    }

    /**
     * Get colors mapping
     */
    protected static function colors(): array
    {
        return [];
    }

    /**
     * Get icons mapping
     */
    protected static function icons(): array
    {
        return [];
    }
}