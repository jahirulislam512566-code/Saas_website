<?php
// routes/admin.php

use App\Http\Controllers\Admin\{
    ActivityController,
    AnalyticsController,
    BackupController,
    BillingController,
    CategoryController,
    CustomerController,
    DashboardController,
    DomainController,
    GatewayController,
    InvoiceController,
    MediaController,
    MediaFolderController,
    NotificationController,
    OrderController,
    PageComponentController,
    PageController,
    PageSectionController,
    PaymentController,
    PermissionController,
    PlanController,
    PostController,
    ProfileController,
    RefundController,
    ReportController,
    RoleController,
    SEOController,
    SettingsController,
    SubscriptionController,
    SystemController,
    TeamController,
    TicketController,
    UserController,
    WebsiteController,
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| All admin routes are prefixed with 'admin' and have the 'admin' middleware
| applied. These routes are only accessible to authenticated users with
| admin permissions.
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'admin'])
    ->group(function () {

        // ============================================
        // DASHBOARD
    
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/health', [DashboardController::class, 'health'])->name('health');
        
        // Alternative: If you want both /admin/dashboard and /admin/dashboard/health
        Route::get('/redirect', [DashboardController::class, 'index'])->name('redirect');
    });
    
    // Dashboard API
    Route::prefix('api/dashboard')->name('api.dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'getDashboardData'])->name('data');
        Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
        Route::get('/chart', [DashboardController::class, 'getChartData'])->name('chart');
        Route::get('/activities', [DashboardController::class, 'getRecentActivities'])->name('activities');
        Route::get('/subscriptions', [DashboardController::class, 'getRecentSubscriptions'])->name('subscriptions');
    });

        // ============================================
        // PROFILE
        // ============================================
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
            Route::get('/', [ProfileController::class, 'edit'])->name('edit.redirect');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
            Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
            Route::get('/password', [ProfileController::class, 'password'])->name('password');
            Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
            Route::get('/activity', [ProfileController::class, 'activity'])->name('activity');
            Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
            Route::post('/notifications/read-all', [ProfileController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
            Route::post('/notifications/{id}/read', [ProfileController::class, 'markNotificationRead'])->name('notifications.read');
            Route::delete('/notifications/{id}', [ProfileController::class, 'deleteNotification'])->name('notifications.delete');
            Route::get('/two-factor', [ProfileController::class, 'twoFactor'])->name('two-factor');
            Route::post('/two-factor/enable', [ProfileController::class, 'enableTwoFactor'])->name('two-factor.enable');
            Route::post('/two-factor/confirm', [ProfileController::class, 'confirmTwoFactor'])->name('two-factor.confirm');
            Route::post('/two-factor/disable', [ProfileController::class, 'disableTwoFactor'])->name('two-factor.disable');
            Route::get('/delete-account', [ProfileController::class, 'deleteAccount'])->name('delete-account');
            Route::delete('/delete-account', [ProfileController::class, 'destroyAccount'])->name('destroy-account');
        });

        // ============================================
        // USER MANAGEMENT
        // ============================================
        Route::resource('users', UserController::class);
        Route::prefix('users')->name('users.')->group(function () {
            Route::patch('{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('export', [UserController::class, 'export'])->name('export');
            Route::get('{user}/profile', [UserController::class, 'profile'])->name('profile');
            Route::get('{user}/activity', [UserController::class, 'activity'])->name('activity');
            Route::get('{user}/subscriptions', [UserController::class, 'subscriptions'])->name('subscriptions');
            Route::get('trash', [UserController::class, 'trash'])->name('trash');
            Route::patch('{id}/restore', [UserController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete');
            Route::get('search', [UserController::class, 'search'])->name('search');
            Route::post('bulk-delete', [UserController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
            Route::post('{user}/impersonate', [UserController::class, 'impersonate'])->name('impersonate');
            Route::post('stop-impersonate', [UserController::class, 'stopImpersonate'])->name('stop-impersonate');
        });

        // ============================================
        // ROLES & PERMISSIONS
        // ============================================
        Route::resource('roles', RoleController::class);
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('{role}/permissions', [RoleController::class, 'permissions'])->name('permissions');
            Route::post('{role}/permissions', [RoleController::class, 'updatePermissions'])->name('update-permissions');
            Route::get('{role}/users', [RoleController::class, 'users'])->name('users');
            Route::post('{role}/users', [RoleController::class, 'assignUsers'])->name('assign-users');
            Route::delete('{role}/users/{user}', [RoleController::class, 'removeUser'])->name('remove-user');
            Route::get('export', [RoleController::class, 'export'])->name('export');
            Route::post('{role}/toggle', [RoleController::class, 'toggle'])->name('toggle');
        });

        Route::resource('permissions', PermissionController::class)->except(['show']);
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('groups', [PermissionController::class, 'groups'])->name('groups');
            Route::post('sync', [PermissionController::class, 'sync'])->name('sync');
            Route::get('export', [PermissionController::class, 'export'])->name('export');
            Route::post('import', [PermissionController::class, 'import'])->name('import');
        });

        // ============================================
        // BILLING & SUBSCRIPTIONS
        // ============================================
        Route::resource('plans', PlanController::class);
        Route::prefix('plans')->name('plans.')->group(function () {
            Route::patch('{plan}/toggle', [PlanController::class, 'toggle'])->name('toggle');
            Route::post('{plan}/duplicate', [PlanController::class, 'duplicate'])->name('duplicate');
            Route::post('reorder', [PlanController::class, 'reorder'])->name('reorder');
            Route::get('export', [PlanController::class, 'export'])->name('export');
            Route::get('import', [PlanController::class, 'importForm'])->name('import.form');
            Route::post('import', [PlanController::class, 'import'])->name('import');
            Route::get('analytics', [PlanController::class, 'analytics'])->name('analytics');
            Route::get('search', [PlanController::class, 'search'])->name('search');
            Route::get('compare', [PlanController::class, 'compare'])->name('compare');
        });

        Route::resource('subscriptions', SubscriptionController::class);
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::post('{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
            Route::post('{subscription}/resume', [SubscriptionController::class, 'resume'])->name('resume');
            Route::post('{subscription}/pause', [SubscriptionController::class, 'pause'])->name('pause');
            Route::post('{subscription}/resume-paused', [SubscriptionController::class, 'resumePaused'])->name('resume-paused');
            Route::post('{subscription}/update-plan', [SubscriptionController::class, 'updatePlan'])->name('update-plan');
            Route::post('{subscription}/renew', [SubscriptionController::class, 'renew'])->name('renew');
            Route::post('bulk-cancel', [SubscriptionController::class, 'bulkCancel'])->name('bulk-cancel');
            Route::post('bulk-pause', [SubscriptionController::class, 'bulkPause'])->name('bulk-pause');
            Route::get('export', [SubscriptionController::class, 'export'])->name('export');
            Route::get('metrics', [SubscriptionController::class, 'metrics'])->name('metrics');
            Route::get('analytics', [SubscriptionController::class, 'analytics'])->name('analytics');
            Route::get('churn-rate', [SubscriptionController::class, 'churnRate'])->name('churn-rate');
        });

        Route::resource('payments', PaymentController::class)->only(['index', 'show']);
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::post('{payment}/refund', [PaymentController::class, 'refund'])->name('refund');
            Route::post('{payment}/void', [PaymentController::class, 'void'])->name('void');
            Route::get('export', [PaymentController::class, 'export'])->name('export');
            Route::get('{payment}/invoice', [PaymentController::class, 'invoice'])->name('invoice');
            Route::get('{payment}/invoice/download', [PaymentController::class, 'downloadInvoice'])->name('invoice.download');
            Route::get('search', [PaymentController::class, 'search'])->name('search');
        });

        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/', [BillingController::class, 'index'])->name('index');
            Route::get('/dashboard', [BillingController::class, 'index'])->name('dashboard');
            Route::get('/gateways', [GatewayController::class, 'index'])->name('gateways');
            Route::get('/gateways/{gateway}/config', [GatewayController::class, 'config'])->name('gateways.config');
            Route::post('/gateways/{gateway}', [GatewayController::class, 'update'])->name('gateways.update');
            Route::post('/gateways/test', [GatewayController::class, 'test'])->name('gateways.test');
            Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices');
            Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.view');
            Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
            Route::post('/invoices/generate', [InvoiceController::class, 'generate'])->name('invoices.generate');
            Route::patch('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
            Route::patch('/invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
            Route::get('/invoices/export', [InvoiceController::class, 'export'])->name('invoices.export');
            Route::get('/refunds', [RefundController::class, 'index'])->name('refunds');
            Route::get('/refunds/{refund}', [RefundController::class, 'show'])->name('refunds.show');
            Route::post('/refunds', [RefundController::class, 'store'])->name('refunds.store');
            Route::patch('/refunds/{refund}/process', [RefundController::class, 'process'])->name('refunds.process');
            Route::patch('/refunds/{refund}/cancel', [RefundController::class, 'cancel'])->name('refunds.cancel');
            Route::get('/refunds/export', [RefundController::class, 'export'])->name('refunds.export');
        });

        // ============================================
        // ORDERS & CUSTOMERS
        // ============================================
        Route::resource('orders', OrderController::class);
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::patch('{order}/process', [OrderController::class, 'process'])->name('process');
            Route::patch('{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
            Route::get('{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
            Route::get('export', [OrderController::class, 'export'])->name('export');
        });

        Route::resource('customers', CustomerController::class);
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('{customer}/orders', [CustomerController::class, 'orders'])->name('orders');
            Route::get('export', [CustomerController::class, 'export'])->name('export');
            Route::post('{customer}/toggle', [CustomerController::class, 'toggle'])->name('toggle');
        });

        // ============================================
        // CONTENT MANAGEMENT (CMS)
        // ============================================
        Route::resource('posts', PostController::class);
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('dashboard', [PostController::class, 'dashboard'])->name('dashboard');
            Route::post('{post}/publish', [PostController::class, 'publish'])->name('publish');
            Route::post('{post}/unpublish', [PostController::class, 'unpublish'])->name('unpublish');
            Route::post('{post}/archive', [PostController::class, 'archive'])->name('archive');
            Route::post('{post}/duplicate', [PostController::class, 'duplicate'])->name('duplicate');
            Route::post('{post}/remove-image', [PostController::class, 'removeImage'])->name('remove-image');
            Route::post('bulk-delete', [PostController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('bulk-publish', [PostController::class, 'bulkPublish'])->name('bulk-publish');
            Route::post('bulk-archive', [PostController::class, 'bulkArchive'])->name('bulk-archive');
            Route::get('export', [PostController::class, 'export'])->name('export');
        });

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::post('reorder', [CategoryController::class, 'reorder'])->name('reorder');
            Route::get('tree', [CategoryController::class, 'tree'])->name('tree');
            Route::get('export', [CategoryController::class, 'export'])->name('export');
            Route::get('search', [CategoryController::class, 'search'])->name('search');
            Route::post('{category}/activate', [CategoryController::class, 'activate'])->name('activate');
            Route::post('{category}/deactivate', [CategoryController::class, 'deactivate'])->name('deactivate');
            Route::delete('{category}/image', [CategoryController::class, 'deleteImage'])->name('image.delete');
        });

        Route::resource('pages', PageController::class);
        Route::prefix('pages')->name('pages.')->group(function () {
            Route::get('export', [PageController::class, 'export'])->name('export');
            Route::post('{page}/duplicate', [PageController::class, 'duplicate'])->name('duplicate');
            Route::post('{page}/publish', [PageController::class, 'publish'])->name('publish');
            Route::post('{page}/unpublish', [PageController::class, 'unpublish'])->name('unpublish');
            Route::get('{page}/sections', [PageSectionController::class, 'index'])->name('sections');
            Route::post('{page}/sections', [PageSectionController::class, 'store'])->name('sections.store');
            Route::put('sections/{section}', [PageSectionController::class, 'update'])->name('sections.update');
            Route::delete('sections/{section}', [PageSectionController::class, 'destroy'])->name('sections.destroy');
            Route::get('sections/{section}/edit', [PageSectionController::class, 'edit'])->name('sections.edit');
            Route::post('sections/reorder', [PageSectionController::class, 'reorder'])->name('sections.reorder');
            Route::post('components', [PageComponentController::class, 'store'])->name('components.store');
            Route::put('components/{component}', [PageComponentController::class, 'update'])->name('components.update');
            Route::delete('components/{component}', [PageComponentController::class, 'destroy'])->name('components.destroy');
            Route::get('components/{component}/edit', [PageComponentController::class, 'edit'])->name('components.edit');
            Route::post('components/reorder', [PageComponentController::class, 'reorder'])->name('components.reorder');
        });

        // ============================================
        // WEBSITES & DOMAINS
        // ============================================
        Route::resource('websites', WebsiteController::class);
        Route::prefix('websites')->name('websites.')->group(function () {
            Route::get('{website}/publish', [WebsiteController::class, 'publish'])->name('publish');
            Route::post('{website}/publish', [WebsiteController::class, 'publishStore'])->name('publish.store');
            Route::get('{website}/domains', [WebsiteController::class, 'domains'])->name('domains');
            Route::post('{website}/domains', [WebsiteController::class, 'domainStore'])->name('domains.store');
            Route::post('domains/{domain}/verify', [WebsiteController::class, 'domainVerify'])->name('domains.verify');
            Route::post('{website}/domains/{domain}/primary', [WebsiteController::class, 'domainPrimary'])->name('domains.primary');
            Route::delete('{website}/domains/{domain}', [WebsiteController::class, 'domainDestroy'])->name('domains.destroy');
            Route::get('{website}/analytics', [WebsiteController::class, 'analytics'])->name('analytics');
            Route::delete('{website}/screenshot', [WebsiteController::class, 'screenshotDestroy'])->name('screenshot.destroy');
            Route::get('export', [WebsiteController::class, 'export'])->name('export');
            Route::get('{website}/seo', [WebsiteController::class, 'seo'])->name('seo');
            Route::post('{website}/seo', [WebsiteController::class, 'updateSeo'])->name('seo.update');
        });

        Route::resource('domains', DomainController::class);
        Route::prefix('domains')->name('domains.')->group(function () {
            Route::patch('{domain}/verify', [DomainController::class, 'verify'])->name('verify');
            Route::patch('{domain}/primary', [DomainController::class, 'setPrimary'])->name('primary');
            Route::get('export', [DomainController::class, 'export'])->name('export');
            Route::post('check', [DomainController::class, 'checkAvailability'])->name('check');
            Route::get('{domain}/dns', [DomainController::class, 'dnsSettings'])->name('dns');
            Route::post('{domain}/dns', [DomainController::class, 'updateDns'])->name('dns.update');
            Route::post('{domain}/ssl', [DomainController::class, 'renewSSL'])->name('ssl.renew');
        });

        // ============================================
        // TEAMS
        // ============================================
        Route::resource('teams', TeamController::class);
        Route::prefix('teams')->name('teams.')->group(function () {
            Route::get('{team}/members', [TeamController::class, 'members'])->name('members');
            Route::post('{team}/members', [TeamController::class, 'addMember'])->name('members.add');
            Route::patch('{team}/members/{user}', [TeamController::class, 'updateMember'])->name('members.update');
            Route::delete('{team}/members/{user}', [TeamController::class, 'removeMember'])->name('members.remove');
            Route::patch('{team}/toggle', [TeamController::class, 'toggle'])->name('toggle');
            Route::get('export', [TeamController::class, 'export'])->name('export');
        });

        // ============================================
        // MEDIA
        // ============================================
        Route::prefix('media')->name('media.')->group(function () {
            Route::get('/', [MediaController::class, 'library'])->name('library');
            Route::get('/library', [MediaController::class, 'library'])->name('library.redirect');
            Route::get('/upload', [MediaController::class, 'uploadForm'])->name('upload');
            Route::post('/upload', [MediaController::class, 'upload'])->name('upload.store');
            Route::post('/bulk-upload', [MediaController::class, 'bulkUpload'])->name('bulk-upload');
            Route::get('/{media}/edit', [MediaController::class, 'edit'])->name('edit');
            Route::put('/{media}', [MediaController::class, 'update'])->name('update');
            Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [MediaController::class, 'bulkDelete'])->name('bulk-delete');
            Route::get('/folders', [MediaFolderController::class, 'index'])->name('folders');
            Route::post('/folders', [MediaFolderController::class, 'store'])->name('folders.store');
            Route::get('/folders/{folder}/edit', [MediaFolderController::class, 'edit'])->name('folders.edit');
            Route::put('/folders/{folder}', [MediaFolderController::class, 'update'])->name('folders.update');
            Route::delete('/folders/{folder}', [MediaFolderController::class, 'destroy'])->name('folders.destroy');
            Route::get('/browser', [MediaController::class, 'browser'])->name('browser');
            Route::get('/browser-api', [MediaController::class, 'browserApi'])->name('browser-api');
        });

        // ============================================
        // SUPPORT & TICKETS
        // ============================================
        Route::resource('tickets', TicketController::class);
        Route::prefix('tickets')->name('tickets.')->group(function () {
            Route::post('{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
            Route::post('{ticket}/assign', [TicketController::class, 'assign'])->name('assign');
            Route::post('{ticket}/close', [TicketController::class, 'close'])->name('close');
            Route::post('{ticket}/reopen', [TicketController::class, 'reopen'])->name('reopen');
            Route::post('{ticket}/resolve', [TicketController::class, 'resolve'])->name('resolve');
            Route::post('{ticket}/priority', [TicketController::class, 'priority'])->name('priority');
            Route::get('export', [TicketController::class, 'export'])->name('export');
            Route::get('pending', [TicketController::class, 'pending'])->name('pending');
            Route::get('resolved', [TicketController::class, 'resolved'])->name('resolved');
            Route::get('my-tickets', [TicketController::class, 'myTickets'])->name('my-tickets');
            Route::get('analytics', [TicketController::class, 'analytics'])->name('analytics');
            Route::get('{ticket}/history', [TicketController::class, 'history'])->name('history');
        });

        // ============================================
        // ANALYTICS & REPORTS
        // ============================================
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/', [AnalyticsController::class, 'dashboard'])->name('dashboard');
            Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard.redirect');
            Route::get('/visitors', [AnalyticsController::class, 'visitors'])->name('visitors');
            Route::get('/revenue', [AnalyticsController::class, 'revenue'])->name('revenue');
            Route::get('/subscriptions', [AnalyticsController::class, 'subscriptions'])->name('subscriptions');
            Route::get('/reports', [AnalyticsController::class, 'reports'])->name('reports');
            Route::get('/sales', [AnalyticsController::class, 'sales'])->name('sales');
            Route::get('/traffic', [AnalyticsController::class, 'traffic'])->name('traffic');
            Route::get('/data', [AnalyticsController::class, 'getData'])->name('data');
            Route::get('/chart-data', [AnalyticsController::class, 'chartData'])->name('chart-data');
            Route::get('/user-data', [AnalyticsController::class, 'userData'])->name('user-data');
            Route::get('/revenue/mrr-data', [AnalyticsController::class, 'mrrData'])->name('revenue.mrr-data');
            Route::get('/revenue/export', [AnalyticsController::class, 'exportRevenue'])->name('revenue.export');
            Route::get('/sales/trend-data', [AnalyticsController::class, 'salesTrendData'])->name('sales.trend-data');
            Route::get('/sales/export', [AnalyticsController::class, 'exportSales'])->name('sales.export');
            Route::get('/users/growth-data', [AnalyticsController::class, 'userGrowthData'])->name('users.growth-data');
            Route::get('/users/export', [AnalyticsController::class, 'exportUsers'])->name('users.export');
            Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
            Route::get('/export/{type}', [AnalyticsController::class, 'export'])->name('export.type');
            Route::get('/realtime', [AnalyticsController::class, 'realtime'])->name('realtime');
        });

        // Analytics API
        Route::prefix('api/analytics')->name('api.analytics.')->group(function () {
            Route::get('/dashboard', [AnalyticsController::class, 'getDashboardData'])->name('dashboard');
            Route::get('/visitors', [AnalyticsController::class, 'getVisitorsData'])->name('visitors');
            Route::get('/revenue', [AnalyticsController::class, 'getRevenueData'])->name('revenue');
            Route::get('/subscriptions', [AnalyticsController::class, 'getSubscriptionsData'])->name('subscriptions');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/subscriptions', [ReportController::class, 'subscriptions'])->name('subscriptions');
            Route::get('/users', [ReportController::class, 'users'])->name('users');
            Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
            Route::get('/churn', [ReportController::class, 'churn'])->name('churn');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
            Route::get('/export/{type}', [ReportController::class, 'export'])->name('export.type');
            Route::post('/schedule', [ReportController::class, 'schedule'])->name('schedule');
            Route::get('/preview', [ReportController::class, 'preview'])->name('preview');
            Route::post('/send-email', [ReportController::class, 'sendEmail'])->name('send-email');
            Route::get('/data', [ReportController::class, 'getData'])->name('data');
            Route::get('/chart-data', [ReportController::class, 'chartData'])->name('chart-data');
        });

        // ============================================
        // SEO
        // ============================================
        Route::prefix('seo')->name('seo.')->group(function () {
            Route::get('/', [SEOController::class, 'dashboard'])->name('dashboard');
            Route::get('/dashboard', [SEOController::class, 'dashboard'])->name('dashboard.redirect');
            Route::get('/sitemap', [SEOController::class, 'sitemap'])->name('sitemap');
            Route::post('/sitemap/generate', [SEOController::class, 'generateSitemap'])->name('sitemap.generate');
            Route::get('/robots', [SEOController::class, 'robots'])->name('robots');
            Route::put('/robots', [SEOController::class, 'updateRobots'])->name('robots.update');
            Route::post('/robots/reset', [SEOController::class, 'resetRobots'])->name('robots.reset');
            Route::get('/redirects', [SEOController::class, 'redirects'])->name('redirects');
            Route::post('/redirects', [SEOController::class, 'storeRedirect'])->name('redirects.store');
            Route::get('/redirects/{redirect}/edit', [SEOController::class, 'editRedirect'])->name('redirects.edit');
            Route::put('/redirects/{redirect}', [SEOController::class, 'updateRedirect'])->name('redirects.update');
            Route::patch('/redirects/{redirect}/toggle', [SEOController::class, 'toggleRedirect'])->name('redirects.toggle');
            Route::delete('/redirects/{redirect}', [SEOController::class, 'destroyRedirect'])->name('redirects.destroy');
            Route::get('/schema', [SEOController::class, 'schema'])->name('schema');
            Route::post('/schema', [SEOController::class, 'storeSchema'])->name('schema.store');
            Route::get('/schemas/{schema}/edit', [SEOController::class, 'editSchema'])->name('schema.edit');
            Route::put('/schemas/{schema}', [SEOController::class, 'updateSchema'])->name('schema.update');
            Route::delete('/schemas/{schema}', [SEOController::class, 'destroySchema'])->name('schema.destroy');
            Route::get('/schemas/{schema}/preview', [SEOController::class, 'previewSchema'])->name('schema.preview');
            Route::post('/schema/validate', [SEOController::class, 'validateSchema'])->name('schema.validate');
        });

        // ============================================
        // NOTIFICATIONS
        // ============================================
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/dashboard', [NotificationController::class, 'index'])->name('dashboard');
            Route::patch('{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
            Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
            Route::delete('{notification}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::get('announcements', [NotificationController::class, 'announcements'])->name('announcements');
            Route::post('announcements', [NotificationController::class, 'storeAnnouncement'])->name('announcements.store');
            Route::get('announcements/{announcement}/edit', [NotificationController::class, 'editAnnouncement'])->name('announcements.edit');
            Route::put('announcements/{announcement}', [NotificationController::class, 'updateAnnouncement'])->name('announcements.update');
            Route::patch('announcements/{announcement}/publish', [NotificationController::class, 'publishAnnouncement'])->name('announcements.publish');
            Route::delete('announcements/{announcement}', [NotificationController::class, 'destroyAnnouncement'])->name('announcements.destroy');
            Route::get('emails', [NotificationController::class, 'emailTemplates'])->name('emails');
            Route::post('emails', [NotificationController::class, 'storeEmailTemplate'])->name('emails.store');
            Route::get('emails/{template}/edit', [NotificationController::class, 'editEmailTemplate'])->name('emails.edit');
            Route::put('emails/{template}', [NotificationController::class, 'updateEmailTemplate'])->name('emails.update');
            Route::get('emails/{template}/preview', [NotificationController::class, 'previewEmail'])->name('emails.preview');
            Route::post('emails/{template}/duplicate', [NotificationController::class, 'duplicateEmailTemplate'])->name('emails.duplicate');
            Route::delete('emails/{template}', [NotificationController::class, 'destroyEmailTemplate'])->name('emails.destroy');
            Route::post('emails/{template}/test', [NotificationController::class, 'testEmail'])->name('emails.test');
        });

        // ============================================
        // SETTINGS
        // ============================================
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::get('/dashboard', [SettingsController::class, 'index'])->name('dashboard');
            Route::get('/general', [SettingsController::class, 'general'])->name('general');
            Route::get('/payment', [SettingsController::class, 'payment'])->name('payment');
            Route::get('/smtp', [SettingsController::class, 'smtp'])->name('smtp');
            Route::get('/seo', [SettingsController::class, 'seo'])->name('seo');
            Route::get('/social', [SettingsController::class, 'social'])->name('social');
            Route::get('/system', [SettingsController::class, 'system'])->name('system');
            Route::get('/environment', [SettingsController::class, 'environment'])->name('environment');
            Route::get('/security', [SettingsController::class, 'security'])->name('security');
            Route::get('/integrations', [SettingsController::class, 'integrations'])->name('integrations');
            Route::put('/general', [SettingsController::class, 'updateGeneral'])->name('update.general');
            Route::put('/payment', [SettingsController::class, 'updatePayment'])->name('update.payment');
            Route::put('/smtp', [SettingsController::class, 'updateSmtp'])->name('update.smtp');
            Route::post('/smtp/test', [SettingsController::class, 'testSmtp'])->name('test.smtp');
            Route::put('/seo', [SettingsController::class, 'updateSeo'])->name('update.seo');
            Route::put('/social', [SettingsController::class, 'updateSocial'])->name('update.social');
            Route::put('/system', [SettingsController::class, 'updateSystem'])->name('update.system');
            Route::put('/environment', [SettingsController::class, 'updateEnvironment'])->name('update.environment');
            Route::put('/security', [SettingsController::class, 'updateSecurity'])->name('update.security');
            Route::put('/integrations', [SettingsController::class, 'updateIntegrations'])->name('update.integrations');
            Route::post('/cache/clear', [SettingsController::class, 'clearCache'])->name('clear.cache');
            Route::post('/optimize', [SettingsController::class, 'optimize'])->name('optimize');
            Route::post('/reset', [SettingsController::class, 'resetDefault'])->name('reset');
            Route::post('/maintenance/toggle', [SettingsController::class, 'toggleMaintenance'])->name('toggle.maintenance');
            Route::post('/remove/logo', [SettingsController::class, 'removeLogo'])->name('remove.logo');
            Route::post('/remove/favicon', [SettingsController::class, 'removeFavicon'])->name('remove.favicon');
            Route::post('/remove/og-image', [SettingsController::class, 'removeOgImage'])->name('remove.og-image');
            Route::get('/api', [SettingsController::class, 'getSettingsJson'])->name('api');
            Route::post('/api', [SettingsController::class, 'updateSettingsApi'])->name('api.update');
            Route::put('/update', [SettingsController::class, 'update'])->name('update');
            Route::patch('/update', [SettingsController::class, 'update'])->name('update.patch');
        });

        // ============================================
        // SYSTEM & ACTIVITY
        // ============================================

       Route::prefix('system')->name('system.')->group(function () {
    Route::get('/', [SystemController::class, 'index'])->name('index');
    Route::get('/dashboard', [SystemController::class, 'index'])->name('dashboard');
    Route::get('/cache', [SystemController::class, 'cache'])->name('cache');
    Route::post('/cache/clear', [SystemController::class, 'clearCache'])->name('cache.clear');
    Route::post('/cache/clear-all', [SystemController::class, 'clearCache'])->name('cache.clear-all');
    Route::post('/cache/optimize', [SystemController::class, 'optimize'])->name('cache.optimize');
    Route::get('/jobs', [SystemController::class, 'jobs'])->name('jobs');
    Route::post('/jobs/{id}/retry', [SystemController::class, 'retryJob'])->name('jobs.retry');
    Route::post('/jobs/retry-all', [SystemController::class, 'retryAllFailed'])->name('jobs.retry-all');
    Route::delete('/jobs/{id}', [SystemController::class, 'cancelJob'])->name('jobs.cancel');
    Route::delete('/jobs/flush', [SystemController::class, 'flushJobs'])->name('jobs.flush');
    Route::get('/queues', [SystemController::class, 'queues'])->name('queues');
    Route::get('/queues/monitor', [SystemController::class, 'monitorQueues'])->name('queues.monitor');
    Route::post('/queues/pause', [SystemController::class, 'pauseQueue'])->name('queues.pause');
    Route::post('/queues/resume', [SystemController::class, 'resumeQueue'])->name('queues.resume');
    Route::post('/queues/restart', [SystemController::class, 'restartQueue'])->name('queues.restart');
    
    // Add the missing route that your view is looking for
    Route::get('/logs', [SystemController::class, 'logs'])->name('logs');
});

        Route::prefix('activities')->name('activities.')->group(function () {
            Route::get('/', [ActivityController::class, 'index'])->name('index');
            Route::get('/export', [ActivityController::class, 'export'])->name('export');
            Route::delete('/clear', [ActivityController::class, 'clear'])->name('clear');
            Route::get('/{activity}', [ActivityController::class, 'show'])->name('show');
            Route::post('/{activity}/delete', [ActivityController::class, 'delete'])->name('delete');
            Route::post('/filter', [ActivityController::class, 'filter'])->name('filter');
            Route::post('/view', [ActivityController::class, 'setView'])->name('view');
        });

        // ============================================
        // BACKUPS
        // ============================================
        Route::prefix('backups')->name('backups.')->group(function () {
            Route::get('/', [BackupController::class, 'index'])->name('index');
            Route::get('/dashboard', [BackupController::class, 'index'])->name('dashboard');
            Route::post('/create', [BackupController::class, 'create'])->name('create');
            Route::get('{backup}/download', [BackupController::class, 'download'])->name('download');
            Route::post('/restore', [BackupController::class, 'restore'])->name('restore');
            Route::delete('{backup}', [BackupController::class, 'destroy'])->name('destroy');
            Route::post('/schedule', [BackupController::class, 'schedule'])->name('schedule');
            Route::post('/clean', [BackupController::class, 'clean'])->name('clean');
            Route::get('/export', [BackupController::class, 'export'])->name('export');
        });

        // ============================================
        // API ROUTES (Frontend Fetch)
        // ============================================
        Route::prefix('api')->name('api.')->group(function () {
            // Plans
            Route::get('/plans', [PlanController::class, 'apiIndex'])->name('plans.index');
            Route::get('/plans/{identifier}', [PlanController::class, 'apiShow'])->name('plans.show');
            Route::get('/plans/stats', [PlanController::class, 'apiStats'])->name('plans.stats');
            Route::post('/plans/compare', [PlanController::class, 'apiCompare'])->name('plans.compare');
            Route::post('/plans/check-slug', [PlanController::class, 'checkSlug'])->name('plans.check-slug');

            // Subscriptions
            Route::get('/subscriptions', [SubscriptionController::class, 'apiIndex'])->name('subscriptions.index');
            Route::get('/subscriptions/stats', [SubscriptionController::class, 'apiStats'])->name('subscriptions.stats');
            Route::get('/subscriptions/{id}', [SubscriptionController::class, 'apiShow'])->name('subscriptions.show');
            Route::get('/subscriptions/analytics', [SubscriptionController::class, 'apiAnalytics'])->name('subscriptions.analytics');
            Route::get('/subscriptions/churn-rate', [SubscriptionController::class, 'apiChurnRate'])->name('subscriptions.churn-rate');
            Route::post('/subscriptions/{id}/cancel', [SubscriptionController::class, 'apiCancel'])->name('subscriptions.cancel');
            Route::post('/subscriptions/{id}/resume', [SubscriptionController::class, 'apiResume'])->name('subscriptions.resume');
            Route::post('/subscriptions/{id}/pause', [SubscriptionController::class, 'apiPause'])->name('subscriptions.pause');

            // Categories
            Route::get('/categories', [CategoryController::class, 'apiIndex'])->name('categories.index');
            Route::get('/categories/tree', [CategoryController::class, 'apiTree'])->name('categories.tree');
            Route::get('/categories/{id}', [CategoryController::class, 'apiShow'])->name('categories.show');
            Route::post('/categories/search', [CategoryController::class, 'apiSearch'])->name('categories.search');
        });
    });