<?php

namespace App\Rules\File;

use App\Rules\BaseRule;
use Illuminate\Http\UploadedFile;

class FileSizeRule extends BaseRule
{
    protected ?int $minSize;
    protected ?int $maxSize;
    protected string $unit;

    public function __construct(
        ?int $minSize = null,
        ?int $maxSize = null,
        string $unit = 'KB'
    ) {
        $this->minSize = $minSize;
        $this->maxSize = $maxSize;
        $this->unit = strtoupper($unit);
    }

    public function passes($attribute, $value): bool
    {
        if (!$value instanceof UploadedFile) {
            return false;
        }

        $size = $value->getSize();

        // Convert to specified unit
        $sizeInUnit = $this->convertToUnit($size);

        if ($this->minSize !== null && $sizeInUnit < $this->minSize) {
            return false;
        }

        if ($this->maxSize !== null && $sizeInUnit > $this->maxSize) {
            return false;
        }

        return true;
    }

    protected function convertToUnit(int $bytes): float
    {
        switch ($this->unit) {
            case 'KB':
                return $bytes / 1024;
            case 'MB':
                return $bytes / (1024 * 1024);
            case 'GB':
                return $bytes / (1024 * 1024 * 1024);
            default:
                return $bytes;
        }
    }

    public function message(): string
    {
        $requirements = [];

        if ($this->minSize !== null && $this->maxSize !== null) {
            $requirements[] = "between {$this->minSize} and {$this->maxSize} {$this->unit}";
        } elseif ($this->minSize !== null) {
            $requirements[] = "at least {$this->minSize} {$this->unit}";
        } elseif ($this->maxSize !== null) {
            $requirements[] = "at most {$this->maxSize} {$this->unit}";
        }

        return "The file size must be " . implode(', ', $requirements) . ".";
    }
}