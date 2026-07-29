<?php

namespace App\Actions\Service;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateServiceAction
{
    /**
     * Update a service
     *
     * @param Service $service
     * @param array $data
     * @return Service
     */
    public function execute(Service $service, array $data): Service
    {
        return DB::transaction(function () use ($service, $data) {
            $oldData = $service->toArray();

            $service->update([
                'name' => $data['name'] ?? $service->name,
                'description' => $data['description'] ?? $service->description,
                'service_type' => $data['service_type'] ?? $service->service_type,
                'category_id' => $data['category_id'] ?? $service->category_id,
                'price' => $data['price'] ?? $service->price,
                'price_type' => $data['price_type'] ?? $service->price_type,
                'duration' => $data['duration'] ?? $service->duration,
                'features' => $data['features'] ?? $service->features,
                'requirements' => $data['requirements'] ?? $service->requirements,
                'is_active' => $data['is_active'] ?? $service->is_active,
                'is_featured' => $data['is_featured'] ?? $service->is_featured,
                'updated_by' => auth()->id(),
            ]);

            // Update packages if provided
            if (isset($data['packages'])) {
                $service->packages()->delete();
                $service->packages()->createMany($data['packages']);
            }

            Log::info('Service updated', [
                'service_id' => $service->id,
                'changes' => array_diff_assoc($service->toArray(), $oldData),
                'updated_by' => auth()->id()
            ]);

            return $service->fresh()->load('packages');
        });
    }
}