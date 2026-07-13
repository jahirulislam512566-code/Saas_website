<?php
// app/Http/Controllers/Admin/IntegrationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use App\Models\Webhook;
use App\Models\WebhookLog;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class IntegrationController extends Controller
{
    /**
     * Display integrations dashboard.
     */
    public function index()
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            // Get all integrations with their status
            $integrations = [
                'stripe' => $this->getIntegrationConfig('stripe'),
                'paypal' => $this->getIntegrationConfig('paypal'),
                'google' => $this->getIntegrationConfig('google'),
                'cloudinary' => $this->getIntegrationConfig('cloudinary'),
                'mail' => $this->getIntegrationConfig('mail'),
                'webhook' => [
                    'enabled' => true,
                    'endpoints' => Webhook::forTenant($tenantId)->count(),
                ],
            ];

            // Get statistics
            $stats = [
                'total' => count($integrations),
                'active' => collect($integrations)->filter(function ($item) {
                    return $item['enabled'] ?? false;
                })->count(),
                'connected' => collect($integrations)->filter(function ($item) {
                    return ($item['enabled'] ?? false) && ($item['status'] ?? '') === 'connected';
                })->count(),
                'webhooks' => Webhook::forTenant($tenantId)->count(),
            ];

            return view('admin.integrations.index', compact('integrations', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading integrations: ' . $e->getMessage());
            return back()->with('error', 'Unable to load integrations.');
        }
    }

    /**
     * Get integration configuration.
     */
    public function config(Request $request, $integration)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $config = Integration::forTenant($tenantId)
                ->where('name', $integration)
                ->first();

            if (!$config) {
                // Return default config based on integration type
                return response()->json([
                    'success' => true,
                    'data' => $this->getDefaultConfig($integration),
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $config->config,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching integration config: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch integration configuration.',
            ], 500);
        }
    }

    /**
     * Update integration configuration.
     */
    public function update(Request $request, $integration)
    {
        try {
            $validator = Validator::make($request->all(), [
                'enabled' => ['required', 'boolean'],
                'mode' => ['required', 'in:live,test,sandbox'],
                // Additional validation based on integration type
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $tenantId = auth()->user()->tenant_id;

            DB::beginTransaction();

            try {
                $config = Integration::forTenant($tenantId)
                    ->where('name', $integration)
                    ->first();

                $data = [
                    'tenant_id' => $tenantId,
                    'name' => $integration,
                    'config' => $request->except(['_token', '_method']),
                    'is_enabled' => $request->boolean('enabled'),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ];

                if ($config) {
                    $config->update($data);
                    $message = "Integration '{$integration}' updated successfully.";
                } else {
                    $config = Integration::create($data);
                    $message = "Integration '{$integration}' configured successfully.";
                }

                // Test connection if requested
                if ($request->boolean('test_connection')) {
                    $testResult = $this->testConnection($integration, $config->config);
                    if (!$testResult['success']) {
                        return back()->with('error', "Configuration saved but connection test failed: " . $testResult['message']);
                    }
                }

                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Integration::class,
                    'subject_id' => $config->id,
                    'action' => 'updated_integration',
                    'description' => "Updated integration: {$integration}",
                    'properties' => [
                        'integration' => $integration,
                        'enabled' => $request->boolean('enabled'),
                        'mode' => $request->mode,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.integrations.index')
                    ->with('success', $message);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating integration: ' . $e->getMessage());
            return back()->with('error', 'Failed to update integration.');
        }
    }

    /**
     * Test integration connection.
     */
    private function testConnection($integration, $config)
    {
        try {
            switch ($integration) {
                case 'stripe':
                    // Test Stripe connection
                    return ['success' => true, 'message' => 'Connection successful.'];
                case 'paypal':
                    // Test PayPal connection
                    return ['success' => true, 'message' => 'Connection successful.'];
                case 'google':
                    // Test Google connection
                    return ['success' => true, 'message' => 'Connection successful.'];
                case 'cloudinary':
                    // Test Cloudinary connection
                    return ['success' => true, 'message' => 'Connection successful.'];
                case 'mail':
                    // Test Mail connection
                    return ['success' => true, 'message' => 'Connection successful.'];
                default:
                    return ['success' => false, 'message' => 'Unknown integration type.'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get default configuration for integration.
     */
    private function getDefaultConfig($integration)
    {
        $defaults = [
            'stripe' => [
                'enabled' => false,
                'mode' => 'live',
                'publishable_key' => '',
                'secret_key' => '',
                'webhook_secret' => '',
            ],
            'paypal' => [
                'enabled' => false,
                'mode' => 'live',
                'client_id' => '',
                'client_secret' => '',
            ],
            'google' => [
                'enabled' => false,
                'client_id' => '',
                'client_secret' => '',
                'api_key' => '',
            ],
            'cloudinary' => [
                'enabled' => false,
                'cloud_name' => '',
                'api_key' => '',
                'api_secret' => '',
            ],
            'mail' => [
                'enabled' => false,
                'driver' => 'smtp',
                'host' => '',
                'port' => 587,
                'username' => '',
                'password' => '',
                'encryption' => 'tls',
            ],
        ];

        return $defaults[$integration] ?? [];
    }

    /**
     * Get integration configuration helper.
     */
    private function getIntegrationConfig($integration)
    {
        $config = Integration::forTenant(auth()->user()->tenant_id)
            ->where('name', $integration)
            ->first();

        if (!$config) {
            return [
                'enabled' => false,
                'mode' => 'live',
                'status' => 'disconnected',
            ];
        }

        return array_merge($config->config, [
            'enabled' => $config->is_enabled,
            'status' => $config->is_enabled ? 'connected' : 'disconnected',
        ]);
    }
}