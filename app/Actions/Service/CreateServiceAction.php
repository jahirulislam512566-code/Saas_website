<?php

namespace App\Actions\Service;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateServiceAction
{
    /**
     * Create a new service
     *
     * @param array $data
     * @return Service
     */
    public function execute(array $data): Service
    {
        return DB::transaction(function () use ($data) {
            $service = Service::create([
                'name' => $data['name'],
                'slug' => \Illuminate\Support\Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'service_type' => $data['service_type'], // website_design, web_development, seo, maintenance, hosting
                'category_id' => $data['category_id'] ?? null,
                'price' => $data['price'] ?? null,
                'price_type' => $data['price_type'] ?? 'fixed', // fixed, hourly, monthly
                'duration' => $data['duration'] ?? null,
                'features' => $data['features'] ?? [],
                'requirements' => $data['requirements'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'is_featured' => $data['is_featured'] ?? false,
                'created_by' => auth()->id(),
            ]);

            // Add service packages if provided
            if (isset($data['packages']) && is_array($data['packages'])) {
                $service->packages()->createMany($data['packages']);
            }

            Log::info('Service created', [
                'service_id' => $service->id,
                'service_type' => $service->service_type,
                'name' => $service->name,
                'created_by' => auth()->id()
            ]);

            return $service->load('packages');
        });
    }
}