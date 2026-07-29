<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\WebsiteController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ComponentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SEOController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\LiveChatController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AIController;
use App\Http\Controllers\Admin\IntegrationController;
use App\Http\Controllers\Admin\DomainController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\MaintenanceController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    // ============================================
    // DASHBOARD
    // ============================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/refresh', [DashboardController::class, 'refresh'])->name('dashboard.refresh');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/dashboard/widgets', [DashboardController::class, 'widgets'])->name('dashboard.widgets');

    // ============================================
    // WEBSITE BUILDER
    // ============================================
    Route::prefix('websites')->name('websites.')->group(function () {
        Route::get('/', [WebsiteController::class, 'index'])->name('index');
        Route::get('/create', [WebsiteController::class, 'create'])->name('create');
        Route::post('/', [WebsiteController::class, 'store'])->name('store');
        Route::get('/{website}', [WebsiteController::class, 'show'])->name('show');
        Route::get('/{website}/edit', [WebsiteController::class, 'edit'])->name('edit');
        Route::put('/{website}', [WebsiteController::class, 'update'])->name('update');
        Route::delete('/{website}', [WebsiteController::class, 'destroy'])->name('destroy');
        Route::post('/{website}/duplicate', [WebsiteController::class, 'duplicate'])->name('duplicate');
        Route::post('/{website}/publish', [WebsiteController::class, 'publish'])->name('publish');
        Route::post('/{website}/screenshot', [WebsiteController::class, 'generateScreenshot'])->name('screenshot');
        Route::get('/{website}/analytics', [WebsiteController::class, 'analytics'])->name('analytics');
    });

    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', [TemplateController::class, 'index'])->name('index');
        Route::get('/create', [TemplateController::class, 'create'])->name('create');
        Route::post('/', [TemplateController::class, 'store'])->name('store');
        Route::get('/{template}', [TemplateController::class, 'show'])->name('show');
        Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('edit');
        Route::put('/{template}', [TemplateController::class, 'update'])->name('update');
        Route::delete('/{template}', [TemplateController::class, 'destroy'])->name('destroy');
        Route::post('/{template}/activate', [TemplateController::class, 'activate'])->name('activate');
        Route::post('/{template}/preview', [TemplateController::class, 'preview'])->name('preview');
    });

    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/create', [PageController::class, 'create'])->name('create');
        Route::post('/', [PageController::class, 'store'])->name('store');
        Route::get('/{page}', [PageController::class, 'show'])->name('show');
        Route::get('/{page}/edit', [PageController::class, 'edit'])->name('edit');
        Route::put('/{page}', [PageController::class, 'update'])->name('update');
        Route::delete('/{page}', [PageController::class, 'destroy'])->name('destroy');
        Route::post('/{page}/duplicate', [PageController::class, 'duplicate'])->name('duplicate');
        Route::post('/{page}/publish', [PageController::class, 'publish'])->name('publish');
        Route::post('/{page}/unpublish', [PageController::class, 'unpublish'])->name('unpublish');
    });

    Route::prefix('sections')->name('sections.')->group(function () {
        Route::get('/', [SectionController::class, 'index'])->name('index');
        Route::get('/create', [SectionController::class, 'create'])->name('create');
        Route::post('/', [SectionController::class, 'store'])->name('store');
        Route::get('/{section}', [SectionController::class, 'show'])->name('show');
        Route::get('/{section}/edit', [SectionController::class, 'edit'])->name('edit');
        Route::put('/{section}', [SectionController::class, 'update'])->name('update');
        Route::delete('/{section}', [SectionController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [SectionController::class, 'reorder'])->name('reorder');
    });

    Route::prefix('components')->name('components.')->group(function () {
        Route::get('/', [ComponentController::class, 'index'])->name('index');
        Route::get('/create', [ComponentController::class, 'create'])->name('create');
        Route::post('/', [ComponentController::class, 'store'])->name('store');
        Route::get('/{component}', [ComponentController::class, 'show'])->name('show');
        Route::get('/{component}/edit', [ComponentController::class, 'edit'])->name('edit');
        Route::put('/{component}', [ComponentController::class, 'update'])->name('update');
        Route::delete('/{component}', [ComponentController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [ComponentController::class, 'reorder'])->name('reorder');
    });

    // ============================================
    // CRM
    // ============================================
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/{customer}/toggle', [CustomerController::class, 'toggle'])->name('toggle');
        Route::get('/export', [CustomerController::class, 'export'])->name('export');
        Route::post('/bulk-delete', [CustomerController::class, 'bulkDelete'])->name('bulk-delete');
    });

    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [LeadController::class, 'index'])->name('index');
        Route::get('/create', [LeadController::class, 'create'])->name('create');
        Route::post('/', [LeadController::class, 'store'])->name('store');
        Route::get('/{lead}', [LeadController::class, 'show'])->name('show');
        Route::get('/{lead}/edit', [LeadController::class, 'edit'])->name('edit');
        Route::put('/{lead}', [LeadController::class, 'update'])->name('update');
        Route::delete('/{lead}', [LeadController::class, 'destroy'])->name('destroy');
        Route::post('/{lead}/convert', [LeadController::class, 'convert'])->name('convert');
        Route::post('/{lead}/status', [LeadController::class, 'updateStatus'])->name('status');
    });

    Route::prefix('teams')->name('teams.')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::get('/create', [TeamController::class, 'create'])->name('create');
        Route::post('/', [TeamController::class, 'store'])->name('store');
        Route::get('/{team}', [TeamController::class, 'show'])->name('show');
        Route::get('/{team}/edit', [TeamController::class, 'edit'])->name('edit');
        Route::put('/{team}', [TeamController::class, 'update'])->name('update');
        Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');
        Route::post('/{team}/members', [TeamController::class, 'addMember'])->name('members.add');
        Route::delete('/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('members.remove');
    });

    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
        Route::post('/{project}/status', [ProjectController::class, 'updateStatus'])->name('status');
    });

    // ============================================
    // BILLING
    // ============================================
    Route::prefix('plans')->name('plans.')->group(function () {
        Route::get('/', [PlanController::class, 'index'])->name('index');
        Route::get('/create', [PlanController::class, 'create'])->name('create');
        Route::post('/', [PlanController::class, 'store'])->name('store');
        Route::get('/{plan}', [PlanController::class, 'show'])->name('show');
        Route::get('/{plan}/edit', [PlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [PlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [PlanController::class, 'destroy'])->name('destroy');
        Route::post('/{plan}/toggle', [PlanController::class, 'toggle'])->name('toggle');
        Route::post('/{plan}/duplicate', [PlanController::class, 'duplicate'])->name('duplicate');
    });

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/create', [SubscriptionController::class, 'create'])->name('create');
        Route::post('/', [SubscriptionController::class, 'store'])->name('store');
        Route::get('/{subscription}', [SubscriptionController::class, 'show'])->name('show');
        Route::get('/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('edit');
        Route::put('/{subscription}', [SubscriptionController::class, 'update'])->name('update');
        Route::delete('/{subscription}', [SubscriptionController::class, 'destroy'])->name('destroy');
        Route::post('/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
        Route::post('/{subscription}/resume', [SubscriptionController::class, 'resume'])->name('resume');
        Route::post('/{subscription}/pause', [SubscriptionController::class, 'pause'])->name('pause');
        Route::post('/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('renew');
    });

    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::post('/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('mark-paid');
        Route::post('/{invoice}/send', [InvoiceController::class, 'send'])->name('send');
        Route::get('/{invoice}/download', [InvoiceController::class, 'download'])->name('download');
    });

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::post('/{payment}/refund', [PaymentController::class, 'refund'])->name('refund');
        Route::post('/{payment}/void', [PaymentController::class, 'void'])->name('void');
        Route::get('/export', [PaymentController::class, 'export'])->name('export');
    });

    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/', [CouponController::class, 'store'])->name('store');
        Route::get('/{coupon}', [CouponController::class, 'show'])->name('show');
        Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('edit');
        Route::put('/{coupon}', [CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('destroy');
        Route::post('/{coupon}/toggle', [CouponController::class, 'toggle'])->name('toggle');
        Route::post('/validate', [CouponController::class, 'validate'])->name('validate');
    });

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::get('/export', [TransactionController::class, 'export'])->name('export');
    });

    // ============================================
    // CONTENT
    // ============================================
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::get('/create', [BlogController::class, 'create'])->name('create');
        Route::post('/', [BlogController::class, 'store'])->name('store');
        Route::get('/{post}', [BlogController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::put('/{post}', [BlogController::class, 'update'])->name('update');
        Route::delete('/{post}', [BlogController::class, 'destroy'])->name('destroy');
        Route::post('/{post}/publish', [BlogController::class, 'publish'])->name('publish');
        Route::post('/{post}/unpublish', [BlogController::class, 'unpublish'])->name('unpublish');
        Route::post('/{post}/duplicate', [BlogController::class, 'duplicate'])->name('duplicate');
    });

    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        Route::get('/', [PortfolioController::class, 'index'])->name('index');
        Route::get('/create', [PortfolioController::class, 'create'])->name('create');
        Route::post('/', [PortfolioController::class, 'store'])->name('store');
        Route::get('/{item}', [PortfolioController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [PortfolioController::class, 'edit'])->name('edit');
        Route::put('/{item}', [PortfolioController::class, 'update'])->name('update');
        Route::delete('/{item}', [PortfolioController::class, 'destroy'])->name('destroy');
        Route::post('/{item}/feature', [PortfolioController::class, 'feature'])->name('feature');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [CategoryController::class, 'reorder'])->name('reorder');
    });

    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('/create', [TagController::class, 'create'])->name('create');
        Route::post('/', [TagController::class, 'store'])->name('store');
        Route::get('/{tag}/edit', [TagController::class, 'edit'])->name('edit');
        Route::put('/{tag}', [TagController::class, 'update'])->name('update');
        Route::delete('/{tag}', [TagController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('media')->name('media.')->group(function () {
        Route::get('/', [MediaController::class, 'index'])->name('index');
        Route::post('/upload', [MediaController::class, 'upload'])->name('upload');
        Route::post('/bulk-upload', [MediaController::class, 'bulkUpload'])->name('bulk-upload');
        Route::get('/{media}', [MediaController::class, 'show'])->name('show');
        Route::delete('/{media}', [MediaController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [MediaController::class, 'bulkDelete'])->name('bulk-delete');
    });

    Route::prefix('seo')->name('seo.')->group(function () {
        Route::get('/', [SEOController::class, 'index'])->name('index');
        Route::get('/{model}/{id}', [SEOController::class, 'edit'])->name('edit');
        Route::put('/{model}/{id}', [SEOController::class, 'update'])->name('update');
        Route::post('/sitemap/generate', [SEOController::class, 'generateSitemap'])->name('sitemap.generate');
        Route::get('/robots', [SEOController::class, 'robots'])->name('robots');
        Route::put('/robots', [SEOController::class, 'updateRobots'])->name('robots.update');
    });

    // ============================================
    // SUPPORT
    // ============================================
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('index');
        Route::get('/create', [TicketController::class, 'create'])->name('create');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('edit');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('update');
        Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('destroy');
        Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
        Route::post('/{ticket}/close', [TicketController::class, 'close'])->name('close');
        Route::post('/{ticket}/reopen', [TicketController::class, 'reopen'])->name('reopen');
        Route::post('/{ticket}/assign', [TicketController::class, 'assign'])->name('assign');
        Route::post('/{ticket}/priority', [TicketController::class, 'priority'])->name('priority');
    });

    Route::prefix('livechat')->name('livechat.')->group(function () {
        Route::get('/', [LiveChatController::class, 'index'])->name('index');
        Route::get('/{conversation}', [LiveChatController::class, 'show'])->name('show');
        Route::post('/send', [LiveChatController::class, 'sendMessage'])->name('send');
        Route::post('/mark-read', [LiveChatController::class, 'markAsRead'])->name('mark-read');
        Route::post('/end/{conversation}', [LiveChatController::class, 'endChat'])->name('end');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('index');
        Route::get('/create', [AnnouncementController::class, 'create'])->name('create');
        Route::post('/', [AnnouncementController::class, 'store'])->name('store');
        Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit');
        Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update');
        Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy');
        Route::post('/{announcement}/publish', [AnnouncementController::class, 'publish'])->name('publish');
    });

    // ============================================
    // ANALYTICS
    // ============================================
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [AnalyticsController::class, 'reports'])->name('reports');
        Route::get('/logs', [AnalyticsController::class, 'logs'])->name('logs');
        Route::get('/data', [AnalyticsController::class, 'getData'])->name('data');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('export');
        Route::delete('/clear', [ActivityLogController::class, 'clear'])->name('clear');
    });

    // ============================================
    // AI TOOLS
    // ============================================
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/writer', [AIController::class, 'writer'])->name('writer');
        Route::get('/generator', [AIController::class, 'generator'])->name('generator');
        Route::get('/seo', [AIController::class, 'seo'])->name('seo');
        Route::get('/image', [AIController::class, 'image'])->name('image');
        Route::post('/generate', [AIController::class, 'generate'])->name('generate');
        Route::post('/optimize', [AIController::class, 'optimize'])->name('optimize');
        Route::get('/usage', [AIController::class, 'usage'])->name('usage');
        Route::get('/settings', [AIController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [AIController::class, 'updateSettings'])->name('settings.update');
    });

    // ============================================
    // SYSTEM
    // ============================================
    Route::prefix('integrations')->name('integrations.')->group(function () {
        Route::get('/', [IntegrationController::class, 'index'])->name('index');
        Route::post('/{integration}/toggle', [IntegrationController::class, 'toggle'])->name('toggle');
        Route::get('/{integration}/config', [IntegrationController::class, 'config'])->name('config');
        Route::put('/{integration}/config', [IntegrationController::class, 'updateConfig'])->name('config.update');
        Route::post('/{integration}/test', [IntegrationController::class, 'test'])->name('test');
    });

    Route::prefix('domains')->name('domains.')->group(function () {
        Route::get('/', [DomainController::class, 'index'])->name('index');
        Route::get('/create', [DomainController::class, 'create'])->name('create');
        Route::post('/', [DomainController::class, 'store'])->name('store');
        Route::get('/{domain}', [DomainController::class, 'show'])->name('show');
        Route::get('/{domain}/edit', [DomainController::class, 'edit'])->name('edit');
        Route::put('/{domain}', [DomainController::class, 'update'])->name('update');
        Route::delete('/{domain}', [DomainController::class, 'destroy'])->name('destroy');
        Route::post('/{domain}/verify', [DomainController::class, 'verify'])->name('verify');
    });

    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/create', [BackupController::class, 'create'])->name('create');
        Route::get('/{backup}/download', [BackupController::class, 'download'])->name('download');
        Route::delete('/{backup}', [BackupController::class, 'destroy'])->name('destroy');
        Route::post('/schedule', [BackupController::class, 'schedule'])->name('schedule');
        Route::post('/restore/{backup}', [BackupController::class, 'restore'])->name('restore');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::get('/general', [SettingsController::class, 'general'])->name('general');
        Route::get('/payment', [SettingsController::class, 'payment'])->name('payment');
        Route::get('/smtp', [SettingsController::class, 'smtp'])->name('smtp');
        Route::get('/seo', [SettingsController::class, 'seo'])->name('seo');
        Route::get('/social', [SettingsController::class, 'social'])->name('social');
        Route::get('/system', [SettingsController::class, 'system'])->name('system');
        Route::put('/general', [SettingsController::class, 'updateGeneral'])->name('update.general');
        Route::put('/payment', [SettingsController::class, 'updatePayment'])->name('update.payment');
        Route::put('/smtp', [SettingsController::class, 'updateSmtp'])->name('update.smtp');
        Route::post('/smtp/test', [SettingsController::class, 'testSmtp'])->name('test.smtp');
        Route::put('/seo', [SettingsController::class, 'updateSeo'])->name('update.seo');
        Route::put('/social', [SettingsController::class, 'updateSocial'])->name('update.social');
        Route::put('/system', [SettingsController::class, 'updateSystem'])->name('update.system');
        Route::post('/cache/clear', [SettingsController::class, 'clearCache'])->name('clear.cache');
    });

    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::post('/mode/toggle', [MaintenanceController::class, 'toggleMode'])->name('mode.toggle');
        Route::post('/cache/clear', [MaintenanceController::class, 'clearCache'])->name('cache.clear');
        Route::post('/optimize', [MaintenanceController::class, 'optimize'])->name('optimize');
    });

}); // End admin routes