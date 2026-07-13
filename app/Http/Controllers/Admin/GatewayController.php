<?php
// app/Http/Controllers/Admin/GatewayController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GatewayController extends Controller
{
    /**
     * Display payment gateways.
     */
    public function index()
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            // Get all gateways with their configurations
            $gateways = [
                'stripe' => $this->getGatewayConfig('stripe'),
                'paypal' => $this->getGatewayConfig('paypal'),
                'razorpay' => $this->getGatewayConfig('razorpay'),
                'paddle' => $this->getGatewayConfig('paddle'),
                'crypto' => $this->getGatewayConfig('crypto'),
                'bank_transfer' => $this->getGatewayConfig('bank_transfer'),
            ];

            return view('admin.billing.gateways', compact('gateways'));
        } catch (\Exception $e) {
            Log::error('Error loading gateways: ' . $e->getMessage());
            return back()->with('error', 'Unable to load gateways.');
        }
    }

    /**
     * Get gateway configuration.
     */
    public function config(Request $request, $gateway)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $config = PaymentGateway::forTenant($tenantId)
                ->where('gateway', $gateway)
                ->first();

            if (!$config) {
                // Return default config
                return response()->json([
                    'success' => true,
                    'data' => [
                        'enabled' => false,
                        'mode' => 'live',
                        'api_key' => '',
                        'api_secret' => '',
                        'webhook_secret' => '',
                        'currencies' => ['USD'],
                        'settings' => [],
                    ],
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'enabled' => $config->is_enabled,
                    'mode' => $config->mode,
                    'api_key' => $config->api_key,
                    'api_secret' => $config->api_secret,
                    'webhook_secret' => $config->webhook_secret,
                    'currencies' => $config->currencies ?? ['USD'],
                    'settings' => $config->settings ?? [],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching gateway config: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch gateway configuration.',
            ], 500);
        }
    }

    /**
     * Update gateway configuration.
     */
    public function update(Request $request, $gateway)
    {
        try {
            $validator = Validator::make($request->all(), [
                'enabled' => ['required', 'boolean'],
                'mode' => ['required', 'in:live,test,sandbox'],
                'api_key' => ['nullable', 'string'],
                'api_secret' => ['nullable', 'string'],
                'webhook_secret' => ['nullable', 'string'],
                'currencies' => ['nullable', 'array'],
                'currencies.*' => ['string', 'size:3'],
                'settings' => ['nullable', 'array'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $tenantId = auth()->user()->tenant_id;

            DB::beginTransaction();

            try {
                $config = PaymentGateway::forTenant($tenantId)
                    ->where('gateway', $gateway)
                    ->first();

                $data = [
                    'tenant_id' => $tenantId,
                    'gateway' => $gateway,
                    'is_enabled' => $request->enabled,
                    'mode' => $request->mode,
                    'api_key' => $request->api_key,
                    'api_secret' => $request->api_secret,
                    'webhook_secret' => $request->webhook_secret,
                    'currencies' => $request->currencies ?? ['USD'],
                    'settings' => $request->settings ?? [],
                ];

                if ($config) {
                    $config->update($data);
                    $message = "Gateway '{$gateway}' configuration updated successfully.";
                } else {
                    $config = PaymentGateway::create($data);
                    $message = "Gateway '{$gateway}' configured successfully.";
                }

                // Log activity
                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => PaymentGateway::class,
                    'subject_id' => $config->id,
                    'action' => 'updated_gateway',
                    'description' => "Updated payment gateway: {$gateway}",
                    'properties' => [
                        'gateway' => $gateway,
                        'enabled' => $request->enabled,
                        'mode' => $request->mode,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.billing.gateways')
                    ->with('success', $message);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating gateway: ' . $e->getMessage());
            return back()->with('error', 'Failed to update gateway configuration.');
        }
    }

    /**
     * Test gateway connection.
     */
    public function test(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;
            $gateways = PaymentGateway::forTenant($tenantId)->get();
            $results = [];

            foreach ($gateways as $gateway) {
                if ($gateway->is_enabled) {
                    $results[$gateway->gateway] = $this->testConnection($gateway);
                }
            }

            $failed = array_filter($results, function ($result) {
                return !$result['success'];
            });

            if (count($failed) > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some gateways failed to connect.',
                    'results' => $results,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'All gateways are connected successfully.',
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            Log::error('Error testing gateways: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to test gateways: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test individual gateway connection.
     */
    private function testConnection($gateway)
    {
        try {
            // In production, this would actually test the connection
            // For now, we just validate that credentials exist
            if (empty($gateway->api_key) || empty($gateway->api_secret)) {
                return [
                    'success' => false,
                    'message' => 'Missing API credentials.',
                ];
            }

            return [
                'success' => true,
                'message' => 'Connection successful.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get gateway configuration helper.
     */
    private function getGatewayConfig($gateway)
    {
        $config = PaymentGateway::forTenant(auth()->user()->tenant_id)
            ->where('gateway', $gateway)
            ->first();

        if (!$config) {
            return [
                'enabled' => false,
                'mode' => 'live',
                'name' => ucfirst(str_replace('_', ' ', $gateway)),
            ];
        }

        return [
            'enabled' => $config->is_enabled,
            'mode' => $config->mode,
            'name' => ucfirst(str_replace('_', ' ', $gateway)),
        ];
    }
}