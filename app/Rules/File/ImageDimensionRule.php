<?php

namespace App\Rules\File;

use App\Rules\BaseRule;
use Illuminate\Http\UploadedFile;

class ImageDimensionRule extends BaseRule
{
    protected ?int $minWidth;
    protected ?int $maxWidth;
    protected ?int $minHeight;
    protected ?int $maxHeight;
    protected bool $exactDimensions;

    public function __construct(
        ?int $minWidth = null,
        ?int $maxWidth = null,
        ?int $minHeight = null,
        ?int $maxHeight = null,
        bool $exactDimensions = false
    ) {
        $this->minWidth = $minWidth;
        $this->maxWidth = $maxWidth;
        $this->minHeight = $minHeight;
        $this->maxHeight = $maxHeight;
        $this->exactDimensions = $exactDimensions;
    }

    public function passes($attribute, $value): bool
    {
        if (!$value instanceof UploadedFile) {
            return false;
        }

        // Get image dimensions
        list($width, $height) = getimagesize($value->getRealPath());

        if ($this->exactDimensions) {
            return ($this->minWidth === null || $width === $this->minWidth) &&
                   ($this->maxWidth === null || $width === $this->maxWidth) &&
                   ($this->minHeight === null || $height === $this->minHeight) &&
                   ($this->maxHeight === null || $height === $this->maxHeight);
        }

        // Check min width
        if ($this->minWidth !== null && $width < $this->minWidth) {
            return false;
        }

        // Check max width
        if ($this->maxWidth !== null && $width > $this->maxWidth) {
            return false;
        }

        // Check min height
        if ($this->minHeight !== null && $height < $this->minHeight) {
            return false;
        }

        // Check max height
        if ($this->maxHeight !== null && $height > $this->maxHeight) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        $requirements = [];

        if ($this->exactDimensions) {
            return "The image must be exactly {$this->minWidth}x{$this->minHeight} pixels.";
        }

        if ($this->minWidth !== null && $this->maxWidth !== null) {
            $requirements[] = "width between {$this->minWidth} and {$this->maxWidth}px";
        } elseif ($this->minWidth !== null) {
            $requirements[] = "width at least {$this->minWidth}px";
        } elseif ($this->maxWidth !== null) {
            $requirements[] = "width at most {$this->maxWidth}px";
        }

        if ($this->minHeight !== null && $this->maxHeight !== null) {
            $requirements[] = "height between {$this->minHeight} and {$this->maxHeight}px";
        } elseif ($this->minHeight !== null) {
            $requirements[] = "height at least {$this->minHeight}px";
        } elseif ($this->maxHeight !== null) {
            $requirements[] = "height at most {$this->maxHeight}px";
        }

        return "The image dimensions must meet the following requirements: " . implode(', ', $requirements) . ".";
    }
}