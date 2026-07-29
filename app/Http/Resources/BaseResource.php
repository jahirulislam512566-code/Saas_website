<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

abstract class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    abstract public function toArray($request): array;

    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with($request): array
    {
        return [
            'success' => true,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * Customize the response for a request.
     */
    public function withResponse($request, $response): void
    {
        $response->header('X-Resource-Type', class_basename($this->resource));
    }

    /**
     * Get the resource type.
     */
    protected function getResourceType(): string
    {
        return Str::snake(class_basename($this->resource));
    }

    /**
     * Format timestamps.
     */
    protected function formatTimestamps($resource): array
    {
        $data = [];

        if ($resource->created_at) {
            $data['created_at'] = $resource->created_at->toDateTimeString();
            $data['created_at_human'] = $resource->created_at->diffForHumans();
        }

        if ($resource->updated_at) {
            $data['updated_at'] = $resource->updated_at->toDateTimeString();
            $data['updated_at_human'] = $resource->updated_at->diffForHumans();
        }

        if ($resource->deleted_at) {
            $data['deleted_at'] = $resource->deleted_at->toDateTimeString();
            $data['deleted_at_human'] = $resource->deleted_at->diffForHumans();
        }

        return $data;
    }

    /**
     * Format money fields.
     */
    protected function formatMoney($value, $currency = null): ?string
    {
        if (is_null($value)) {
            return null;
        }

        $currency = $currency ?? config('app.currency', 'USD');
        return money($value, $currency);
    }

    /**
     * Format date fields.
     */
    protected function formatDate($date, $format = null): ?string
    {
        if (!$date) {
            return null;
        }

        return formatDate($date, $format);
    }
}