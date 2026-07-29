<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Website\CreateWebsite;
use App\Actions\Website\UpdateWebsite;
use App\Actions\Website\DeleteWebsite;
use App\Actions\Website\PublishWebsite;
use App\Actions\Website\CloneWebsite;
use App\Actions\Template\CreateTemplate;
use App\Actions\Subscription\AssignPlan;
use App\Actions\Media\UploadMedia;
use App\Actions\SEO\GenerateSitemap;
use App\Actions\Invoice\SendInvoice;
use App\Actions\Subscription\CreateSubscription;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    public function createWebsite(Request $request, CreateWebsite $action)
    {
        try {
            $website = $action->execute($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Website created successfully',
                'data' => $website,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function updateWebsite(Request $request, UpdateWebsite $action)
    {
        try {
            $website = $action->execute([
                'website_id' => $request->route('id'),
                ...$request->all(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Website updated successfully',
                'data' => $website,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function deleteWebsite(Request $request, DeleteWebsite $action)
    {
        try {
            $result = $action->execute([
                'website_id' => $request->route('id'),
                'force' => $request->input('force', false),
                'delete_media' => $request->input('delete_media', true),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Website deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function publishWebsite(Request $request, PublishWebsite $action)
    {
        try {
            $website = $action->execute([
                'website_id' => $request->route('id'),
                'make_public' => $request->input('make_public', true),
                'generate_sitemap' => $request->input('generate_sitemap', true),
                'clear_cache' => $request->input('clear_cache', true),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Website published successfully',
                'data' => $website,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function cloneWebsite(Request $request, CloneWebsite $action)
    {
        try {
            $website = $action->execute([
                'source_website_id' => $request->route('id'),
                'user_id' => $request->input('user_id'),
                'name' => $request->input('name'),
                'domain' => $request->input('domain'),
                'clone_media' => $request->input('clone_media', true),
                'clone_templates' => $request->input('clone_templates', true),
                'clone_pages' => $request->input('clone_pages', true),
                'clone_menus' => $request->input('clone_menus', true),
                'clone_forms' => $request->input('clone_forms', true),
                'clone_settings' => $request->input('clone_settings', true),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Website cloned successfully',
                'data' => $website,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function createTemplate(Request $request, CreateTemplate $action)
    {
        try {
            $template = $action->execute($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Template created successfully',
                'data' => $template,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function assignPlan(Request $request, AssignPlan $action)
    {
        try {
            $subscription = $action->execute([
                'user_id' => $request->route('user_id'),
                'plan_id' => $request->input('plan_id'),
                'auto_renew' => $request->input('auto_renew', true),
                'trial_days' => $request->input('trial_days'),
                'activate_immediately' => $request->input('activate_immediately', true),
                'process_payment' => $request->input('process_payment', true),
                'payment_method' => $request->input('payment_method'),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Plan assigned successfully',
                'data' => $subscription,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function uploadMedia(Request $request, UploadMedia $action)
    {
        try {
            $media = $action->execute([
                'website_id' => $request->route('id'),
                'file' => $request->file('file'),
                'name' => $request->input('name'),
                'alt_text' => $request->input('alt_text'),
                'caption' => $request->input('caption'),
                'description' => $request->input('description'),
                'folder' => $request->input('folder', 'media'),
                'generate_thumbnails' => $request->input('generate_thumbnails', true),
                'settings' => $request->input('settings', []),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Media uploaded successfully',
                'data' => $media,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function generateSitemap(Request $request, GenerateSitemap $action)
    {
        try {
            $result = $action->execute([
                'website_id' => $request->route('id'),
                'include_posts' => $request->input('include_posts', true),
                'include_pages' => $request->input('include_pages', true),
                'home_priority' => $request->input('home_priority', '1.0'),
                'page_priority' => $request->input('page_priority', '0.8'),
                'post_priority' => $request->input('post_priority', '0.6'),
                'ping_engines' => $request->input('ping_engines', true),
                'save_to_disk' => $request->input('save_to_disk', true),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Sitemap generated successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function sendInvoice(Request $request, SendInvoice $action)
    {
        try {
            $result = $action->execute([
                'invoice_id' => $request->route('id'),
                'recipients' => $request->input('recipients'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
                'attachments' => $request->input('attachments'),
                'send_copy_to_admin' => $request->input('send_copy_to_admin', false),
                'admin_emails' => $request->input('admin_emails'),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Invoice sent successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }

    public function createSubscription(Request $request, CreateSubscription $action)
    {
        try {
            $subscription = $action->execute([
                'user_id' => $request->input('user_id'),
                'plan_id' => $request->input('plan_id'),
                'status' => $request->input('status', 'pending'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'trial_ends_at' => $request->input('trial_ends_at'),
                'auto_renew' => $request->input('auto_renew', true),
                'payment_method' => $request->input('payment_method'),
                'amount' => $request->input('amount'),
                'currency' => $request->input('currency'),
                'activate' => $request->input('activate', true),
                'send_email' => $request->input('send_email', true),
                'settings' => $request->input('settings', []),
                'metadata' => $request->input('metadata', []),
                'force_create' => $request->input('force_create', false),
                'process_payment' => $request->input('process_payment', false),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Subscription created successfully',
                'data' => $subscription,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof ActionException ? $e->getErrors() : [],
            ], 422);
        }
    }
}
