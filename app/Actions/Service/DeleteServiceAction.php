<?php

namespace App\Actions\Service;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteServiceAction
{
    /**
     * Delete a service
     *
     * @param Service $service
     * @param bool $forceDelete
     * @return bool
     * @throws \Exception
     */
    public function execute(Service $service, bool $forceDelete = false): bool
    {
        return DB::transaction(function () use ($service, $forceDelete) {
            // Check if service has active orders
            if ($service->orders()->where('status', 'active')->exists()) {
                throw new \Exception('Cannot delete service with active orders.');
            }

            if ($forceDelete) {
                $service->packages()->delete();
                $service->forceDelete();
                Log::warning('Service permanently deleted', [
                    'service_id' => $service->id,
                    'name' => $service->name,
                    'deleted_by' => auth()->id()
                ]);
            } else {
                $service->delete();
                Log::info('Service soft deleted', [
                    'service_id' => $service->id,
                    'name' => $service->name,
                    'deleted_by' => auth()->id()
                ]);
            }

            return true;
        });
    }
}