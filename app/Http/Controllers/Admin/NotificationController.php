<?php
// app/Http/Controllers/Admin/NotificationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Announcement;
use App\Models\EmailTemplate;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Display notifications.
     */
    public function index(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Notification::forTenant($tenantId);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('message', 'LIKE', "%{$search}%");
                });
            }

            // Type filter
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Status filter
            if ($request->filled('status')) {
                if ($request->status === 'read') {
                    $query->whereNotNull('read_at');
                } else {
                    $query->whereNull('read_at');
                }
            }

            $notifications = $query->latest()->paginate(15)->withQueryString();

            $stats = [
                'total' => Notification::forTenant($tenantId)->count(),
                'unread' => Notification::forTenant($tenantId)->whereNull('read_at')->count(),
                'announcements' => Notification::forTenant($tenantId)->where('type', 'announcement')->count(),
                'system' => Notification::forTenant($tenantId)->where('type', 'system')->count(),
            ];

            return view('admin.notifications.index', compact('notifications', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch notifications.');
        }
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        try {
            $this->authorizeTenant($notification);

            $notification->update(['read_at' => now()]);

            return back()->with('success', 'Notification marked as read.');
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return back()->with('error', 'Failed to mark notification as read.');
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        try {
            Notification::forTenant(auth()->user()->tenant_id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return back()->with('success', 'All notifications marked as read.');
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return back()->with('error', 'Failed to mark all notifications as read.');
        }
    }

    /**
     * Delete a notification.
     */
    public function destroy(Notification $notification)
    {
        try {
            $this->authorizeTenant($notification);

            $notification->delete();

            return back()->with('success', 'Notification deleted.');
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete notification.');
        }
    }

    /**
     * Display announcements.
     */
    public function announcements(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = Announcement::forTenant($tenantId);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('title', 'LIKE', "%{$search}%")
                      ->orWhere('content', 'LIKE', "%{$search}%");
            }

            $announcements = $query->latest()->paginate(12)->withQueryString();

            $stats = [
                'total' => Announcement::forTenant($tenantId)->count(),
                'published' => Announcement::forTenant($tenantId)->where('status', 'published')->count(),
                'draft' => Announcement::forTenant($tenantId)->where('status', 'draft')->count(),
                'scheduled' => Announcement::forTenant($tenantId)->where('status', 'scheduled')->count(),
            ];

            return view('admin.notifications.announcements.index', compact('announcements', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching announcements: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch announcements.');
        }
    }

    /**
     * Store announcement.
     */
    public function storeAnnouncement(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                'priority' => ['required', 'in:normal,high'],
                'status' => ['required', 'in:draft,published,scheduled'],
                'send_email' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $tenantId = auth()->user()->tenant_id;

                $announcement = Announcement::create([
                    'tenant_id' => $tenantId,
                    'user_id' => auth()->id(),
                    'title' => $request->title,
                    'content' => $request->content,
                    'priority' => $request->priority,
                    'status' => $request->status,
                    'send_email' => $request->has('send_email'),
                    'published_at' => $request->status === 'published' ? now() : null,
                    'created_by' => auth()->id(),
                ]);

                // Create notification for users
                if ($request->status === 'published') {
                    $this->createAnnouncementNotification($announcement);
                }

                Activity::create([
                    'user_id' => auth()->id(),
                    'tenant_id' => $tenantId,
                    'subject_type' => Announcement::class,
                    'subject_id' => $announcement->id,
                    'action' => 'created_announcement',
                    'description' => "Created announcement: {$announcement->title}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.notifications.announcements')
                    ->with('success', "Announcement '{$announcement->title}' created successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error creating announcement: ' . $e->getMessage());
            return back()->with('error', 'Failed to create announcement.');
        }
    }

    /**
     * Update announcement.
     */
    public function updateAnnouncement(Request $request, Announcement $announcement)
    {
        try {
            $this->authorizeTenant($announcement);

            $validator = Validator::make($request->all(), [
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                'priority' => ['required', 'in:normal,high'],
                'status' => ['required', 'in:draft,published,scheduled'],
                'send_email' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            try {
                $oldStatus = $announcement->status;

                $announcement->update([
                    'title' => $request->title,
                    'content' => $request->content,
                    'priority' => $request->priority,
                    'status' => $request->status,
                    'send_email' => $request->has('send_email'),
                    'published_at' => $request->status === 'published' ? ($announcement->published_at ?? now()) : null,
                    'updated_by' => auth()->id(),
                ]);

                // Create notification if published
                if ($oldStatus !== 'published' && $request->status === 'published') {
                    $this->createAnnouncementNotification($announcement);
                }

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Announcement::class,
                    'subject_id' => $announcement->id,
                    'action' => 'updated_announcement',
                    'description' => "Updated announcement: {$announcement->title}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.notifications.announcements')
                    ->with('success', "Announcement '{$announcement->title}' updated successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error updating announcement: ' . $e->getMessage());
            return back()->with('error', 'Failed to update announcement.');
        }
    }

    /**
     * Publish announcement.
     */
    public function publishAnnouncement(Request $request, Announcement $announcement)
    {
        try {
            $this->authorizeTenant($announcement);

            DB::beginTransaction();

            try {
                $announcement->update([
                    'status' => 'published',
                    'published_at' => now(),
                    'updated_by' => auth()->id(),
                ]);

                $this->createAnnouncementNotification($announcement);

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Announcement::class,
                    'subject_id' => $announcement->id,
                    'action' => 'published_announcement',
                    'description' => "Published announcement: {$announcement->title}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.notifications.announcements')
                    ->with('success', "Announcement '{$announcement->title}' published successfully.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error publishing announcement: ' . $e->getMessage());
            return back()->with('error', 'Failed to publish announcement.');
        }
    }

    /**
     * Delete announcement.
     */
    public function destroyAnnouncement(Announcement $announcement)
    {
        try {
            $this->authorizeTenant($announcement);

            DB::beginTransaction();

            try {
                $announcementName = $announcement->title;
                $announcement->delete();

                Activity::create([
                    'user_id' => auth()->id(),
                    'subject_type' => Announcement::class,
                    'subject_id' => $announcement->id,
                    'action' => 'deleted_announcement',
                    'description' => "Deleted announcement: {$announcementName}",
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                DB::commit();

                return redirect()->route('admin.notifications.announcements')
                    ->with('success', "Announcement '{$announcementName}' deleted.");
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error deleting announcement: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete announcement.');
        }
    }

    /**
     * Display email templates.
     */
    public function emailTemplates(Request $request)
    {
        try {
            $tenantId = auth()->user()->tenant_id;

            $query = EmailTemplate::forTenant($tenantId);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('subject', 'LIKE', "%{$search}%");
            }

            $templates = $query->paginate(15)->withQueryString();

            $stats = [
                'total' => EmailTemplate::forTenant($tenantId)->count(),
                'active' => EmailTemplate::forTenant($tenantId)->where('is_active', true)->count(),
                'default' => EmailTemplate::forTenant($tenantId)->where('is_default', true)->count(),
            ];

            return view('admin.notifications.emails.index', compact('templates', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error fetching email templates: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch email templates.');
        }
    }

    /**
     * Preview email template.
     */
    public function previewEmail(EmailTemplate $template)
    {
        try {
            $this->authorizeTenant($template);

            $content = $this->renderTemplate($template);

            return response()->json([
                'success' => true,
                'data' => [
                    'content' => $content,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error previewing email template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to preview template.',
            ], 500);
        }
    }

    /**
     * Create announcement notification.
     */
    private function createAnnouncementNotification($announcement)
    {
        $users = User::forTenant($announcement->tenant_id)->get();

        foreach ($users as $user) {
            Notification::create([
                'tenant_id' => $announcement->tenant_id,
                'user_id' => $user->id,
                'type' => 'announcement',
                'title' => $announcement->title,
                'message' => Str::limit(strip_tags($announcement->content), 200),
                'icon' => 'fa-bullhorn',
                'link' => route('admin.notifications.index'),
                'data' => [
                    'announcement_id' => $announcement->id,
                    'priority' => $announcement->priority,
                ],
            ]);
        }
    }

    /**
     * Render email template with variables.
     */
    private function renderTemplate($template)
    {
        $variables = [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'site_name' => config('app.name'),
            'year' => date('Y'),
            'site_url' => config('app.url'),
        ];

        $content = $template->content;

        foreach ($variables as $key => $value) {
            $content = str_replace('{{ ' . $key . ' }}', $value, $content);
        }

        return $content;
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