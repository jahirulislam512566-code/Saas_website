<?php

namespace App\Services\Media\Processors;

use Illuminate\Http\UploadedFile;

interface ProcessorInterface
{
    /**
     * Process a file
     */
    public function process(UploadedFile $file, array $options = []): ?UploadedFile;

    /**
     * Get processor configuration
     */
    public function getConfig(): array;

    /**
     * Get processor name
     */
    public function getProcessorName(): string;
}