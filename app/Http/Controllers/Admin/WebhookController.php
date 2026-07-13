<?php
// app/Http/Controllers/Admin/WebhookController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Webhook;
use App\Models\WebhookLog;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class WebhookController extends Controller
{
    /**
     * Display webhooks.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Webhook::forTenant($tenantId);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('url', 'LIKE', "%{$search}%");
            }

            $webhooks = $query->latest()->paginate(15)->withQueryString();

            return view('admin.integrations.webhooks', compact('webhooks'));
        } catch (\Exception $e) {
            Log::error('Error fetching webhooks: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch webhooks.');
        }
    }

    /**
     * Store webhook.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'url' => ['required', 'url', 'max:500'],
                'events' => ['required', 'array', 'min:1'],
                'events.*' => ['string'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;

                $webhook = Webhook::create([
                    'tenant_id' => $tenantId,
                    'name' => $request->name,
                    'url' => $request->url,
                    'events' => $request->events,
                    'is_active' => $request->has('is_active'),
                    'created_by' => auth()->id(),
                ]);

                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Webhook::class,
                    'subject_id' => $webhook->id,
                    'action' => 'created_webhook',
                    'description' => "Created webhook: {$webhook->name}",
                    'properties' => [
                        'webhook_name' => $webhook->name,
                        'webhook_url' => $webhook->url,
                        'events' => $webhook->events,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.integrations.webhook')
                    ->with('success', "Webhook '{$webhook->name}' created successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating webhook: ' . $e->getMessage());
            return back()->with('error', 'Failed to create webhook.');
        }
    }

    /**
     * Update webhook.
     */
    public function update(Request $request, Webhook $webhook)
    {
        try {
            $this->authorizeTenant($webhook);

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'url' => ['required', 'url', 'max:500'],
                'events' => ['required', 'array', 'min:1'],
                'events.*' => ['string'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $oldData = [
                    'name' => $webhook->name,
                    'url' => $webhook->url,
                    'events' => $webhook->events,
                    'is_active' => $webhook->is_active,
                ];

                $webhook->update([
                    'name' => $request->name,
                    'url' => $request->url,
                    'events' => $request->events,
                    'is_active' => $request->has('is_active'),
                    'updated_by' => auth()->id(),
                ]);

                $changes = [];
                if ($oldData['name'] !== $request->name) $changes[] = 'name';
                if ($oldData['url'] !== $request->url) $changes[] = 'url';
                if ($oldData['is_active'] !== $request->has('is_active')) $changes[] = 'status';

                if (!empty($changes)) {
                    Activity::create([
                        'user_id' => auth()->id(),
                        'subject_type' => Webhook::class,
                        'subject_id' => $webhook->id,
                        'action' => 'updated_webhook',
                        'description' => "Updated webhook: {$webhook->name}",
                        'properties' => [
                            'webhook_name' => $webhook->name,
                            'changes' => $changes,
                        ],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                DB::commit();

                return redirect()->route('admin.integrations.webhook')
                    ->with('success', "Webhook '{$webhook->name}' updated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating webhook: ' . $e->getMessage());
            return back()->with('error', 'Failed to update webhook.');
        }
    }

    /**
     * Toggle webhook status.
     */
    public function toggle(Webhook $webhook)
    {
        try {
            $this->authorizeTenant($webhook);

            $webhook->update([
                'is_active' => !$webhook->is_active,
                'updated_by' => auth()->id(),
            ]);

            return back()->with('success', "Webhook '{$webhook->name}' status updated.");
        } catch (\Exception $e) {
            Log::error('Error toggling webhook: ' . $e->getMessage());
            return back()->with('error', 'Failed to toggle webhook.');
        }
    }

    /**
     * Delete webhook.
     */
    public function destroy(Webhook $webhook)
    {
        try {
            $this->authorizeTenant($webhook);

            DB::beginTransaction();

            try {
                $webhookName = $webhook->name;

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Webhook::class,
                    'subject_id' => $webhook->id,
                    'action' => 'deleted_webhook',
                    'description' => "Deleted webhook: {$webhookName}",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                $webhook->delete();

                DB::commit();

                return redirect()->route('admin.integrations.webhook')
                    ->with('success', "Webhook '{$webhookName}' deleted.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting webhook: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete webhook.');
        }
    }

    /**
     * Test webhook.
     */
    public function test(Webhook $webhook)
    {
        try {
            $this->authorizeTenant($webhook);

            // Send test payload
            $payload = [
                'event' => 'test.webhook',
                'timestamp' => now()->toISOString(),
                'data' => [
                    'message' => 'This is a test webhook payload',
                    'webhook_id' => $webhook->id,
                    'webhook_name' => $webhook->name,
                ],
            ];

            $response = Http::timeout(10)
                ->post($webhook->url, $payload);

            // Log the attempt
            WebhookLog::create([
                'tenant_id' => $webhook->tenant_id,
                'webhook_id' => $webhook->id,
                'event' => 'test.webhook',
                'payload' => $payload,
                'response' => [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ],
                'status' => $response->successful() ? 'success' : 'failed',
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Webhook test successful!',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Webhook test failed with status: ' . $response->status(),
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error testing webhook: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Webhook test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Authorize tenant.
     */
    protected function authorizeTenant($model)
    {
        if ($model->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Unauthorized action.');
        }
    }
}