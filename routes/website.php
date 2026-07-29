<?php

use App\Http\Controllers\Agency\AboutController as AgencyAboutController;
use App\Http\Controllers\Agency\ContactController as AgencyContactController;
use App\Http\Controllers\Agency\HomeController as AgencyHomeController;
use App\Http\Controllers\Agency\PortfolioController as AgencyPortfolioController;
use App\Http\Controllers\Agency\ServicesController as AgencyServicesController;
use App\Http\Controllers\Restaurant\AboutController as RestaurantAboutController;
use App\Http\Controllers\Restaurant\ContactController as RestaurantContactController;
use App\Http\Controllers\Restaurant\HomeController as RestaurantHomeController;
use App\Http\Controllers\Restaurant\MenuController as RestaurantMenuController;
use App\Http\Controllers\Website\{ HomeController, AboutController as WebsiteAboutController, ServiceController, ContactController as WebsiteContactController, BlogController, PortfolioController as WebsitePortfolioController, TeamController, PricingController, FaqController, PageController, SearchController, NewsletterController, TemplateController, FeatureController, SolutionController, ResourceController, CareerController, AccountController };
use App\Http\Controllers\Website\DocsController;
use App\Http\Controllers\Website\ThemeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
| Note: This file is included from web.php
| All routes here are already prefixed with 'website' or have specific prefixes
*/

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
// ==================== FEATURES ROUTES ====================
Route::prefix('features')->name('features.')->group(function () {
    Route::get('/', [FeatureController::class, 'index'])->name('index');
    Route::get('/{slug}', [FeatureController::class, 'show'])->name('show');
    Route::get('/category/{category}', [FeatureController::class, 'category'])->name('category');
});

// ==================== TEMPLATES ROUTES ====================
Route::prefix('templates')->name('templates.')->group(function () {
    Route::get('/', [TemplateController::class, 'index'])->name('index');
    Route::get('/{slug}', [TemplateController::class, 'show'])->name('show');
    Route::get('/category/{category}', [TemplateController::class, 'category'])->name('category');
    Route::post('/{id}/use', [TemplateController::class, 'useTemplate'])->name('use')->middleware('auth');
});

// ==================== SOLUTIONS ROUTES ====================
Route::prefix('solutions')->name('solutions.')->group(function () {
    Route::get('/', [SolutionController::class, 'index'])->name('index');
    Route::get('/{slug}', [SolutionController::class, 'show'])->name('show');
});

// ==================== RESOURCES ROUTES ====================
Route::prefix('resources')->name('resources.')->group(function () {
    Route::get('/', [ResourceController::class, 'index'])->name('index');
    Route::get('/category/{category}', [ResourceController::class, 'category'])->name('category');
    Route::get('/{slug}', [ResourceController::class, 'show'])->name('show');
});

// ==================== BLOG ROUTES ====================
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::post('/{post}/comment', [BlogController::class, 'storeComment'])->name('comment.store');
});

// Documentation Routes
Route::prefix('docs')->name('docs.')->group(function () {
    Route::get('/', [DocsController::class, 'index'])->name('index');
    Route::get('/search', [DocsController::class, 'search'])->name('search');
    Route::get('/category/{slug}', [DocsController::class, 'category'])->name('category');
    Route::get('/article/{slug}', [DocsController::class, 'article'])->name('article');
    Route::post('/feedback', [DocsController::class, 'feedback'])->name('feedback');
});

// ==================== WEBSITE STATIC PAGES ====================
// These are accessible via /website/about, /website/contact, etc.
Route::prefix('website')->name('website.')->group(function () {
    
    // Static Pages
    Route::get('/about', [WebsiteAboutController::class, 'index'])->name('about');
    Route::get('/contact', [WebsiteContactController::class, 'index'])->name('contact');
    Route::post('/contact', [WebsiteContactController::class, 'store'])->name('contact.store');
    
    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
    
    // Portfolio
    Route::get('/portfolio', [WebsitePortfolioController::class, 'index'])->name('portfolio');
    Route::get('/portfolio/{slug}', [WebsitePortfolioController::class, 'show'])->name('portfolio.show');
    Route::get('/portfolio/category/{slug}', [WebsitePortfolioController::class, 'category'])->name('portfolio.category');
    
    // Team
    Route::get('/team', [TeamController::class, 'index'])->name('team');
    Route::get('/team/{slug}', [TeamController::class, 'show'])->name('team.show');
    
    // Pricing - Remove this if you have it in web.php
    // Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
    
    // FAQ
    Route::get('/faq', [FaqController::class, 'index'])->name('faq');
    
    // Dynamic Pages
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page');
    
    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
    
    // Newsletter
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
    Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
    
    // Sitemap
    Route::get('/sitemap', function () {
        return view('website.sitemap');
    })->name('sitemap');
    
    // Robots.txt
    Route::get('/robots.txt', function () {
        return response()->view('website.robots')->header('Content-Type', 'text/plain');
    })->name('robots');
});

// ==================== CAREERS ROUTES ====================
Route::prefix('careers')->name('careers.')->group(function () {
    Route::get('/', [CareerController::class, 'index'])->name('index');
    Route::get('/{slug}', [CareerController::class, 'show'])->name('show');
    Route::post('/{slug}/apply', [CareerController::class, 'apply'])->name('apply')->middleware('auth');
});

// ==================== ABOUT ROUTES ====================
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/', [WebsiteAboutController::class, 'index'])->name('index');
    Route::get('/team', [TeamController::class, 'index'])->name('team');
});

// ==================== THEME ROUTES ====================
Route::prefix('themes')->name('themes.')->group(function () {
    
    // ============ AGENCY THEME ============
    Route::prefix('agency')->name('agency.')->group(function () {
        Route::get('/', [AgencyHomeController::class, 'index'])->name('home');
        Route::get('/about', [AgencyAboutController::class, 'index'])->name('about');
        Route::get('/services', [AgencyServicesController::class, 'index'])->name('services');
        Route::get('/portfolio', [AgencyPortfolioController::class, 'index'])->name('portfolio');
        Route::get('/contact', [AgencyContactController::class, 'index'])->name('contact');
        Route::post('/contact', [AgencyContactController::class, 'store'])->name('contact.store');
    });
    
    // ============ RESTAURANT THEME ============
    Route::prefix('restaurant')->name('restaurant.')->group(function () {
        Route::get('/', [RestaurantHomeController::class, 'index'])->name('home');
        Route::get('/about', [RestaurantAboutController::class, 'index'])->name('about');
        Route::get('/menu', [RestaurantMenuController::class, 'index'])->name('menu');
        Route::get('/contact', [RestaurantContactController::class, 'index'])->name('contact');
        Route::post('/contact', [RestaurantContactController::class, 'store'])->name('contact.store');
    });
    
    // ============ BUSINESS THEME ============
    Route::prefix('business')->name('business.')->group(function () {
        Route::get('/', [ThemeController::class, 'business'])->name('home');
        Route::get('/about', [ThemeController::class, 'businessAbout'])->name('about');
        Route::get('/services', [ThemeController::class, 'businessServices'])->name('services');
        Route::get('/contact', [ThemeController::class, 'businessContact'])->name('contact');
    });
    
    // ============ MODERN THEME ============
    Route::prefix('modern')->name('modern.')->group(function () {
        Route::get('/', [ThemeController::class, 'modern'])->name('home');
        Route::get('/about', [ThemeController::class, 'modernAbout'])->name('about');
        Route::get('/services', [ThemeController::class, 'modernServices'])->name('services');
        Route::get('/contact', [ThemeController::class, 'modernContact'])->name('contact');
    });
    
    // ============ CREATIVE THEME ============
    Route::prefix('creative')->name('creative.')->group(function () {
        Route::get('/', [ThemeController::class, 'creative'])->name('home');
        Route::get('/about', [ThemeController::class, 'creativeAbout'])->name('about');
        Route::get('/services', [ThemeController::class, 'creativeServices'])->name('services');
        Route::get('/contact', [ThemeController::class, 'creativeContact'])->name('contact');
    });
});

// ==================== PAYMENT ROUTES ====================
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [App\Http\Controllers\Website\CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [App\Http\Controllers\Website\CheckoutController::class, 'process'])->name('process');
    Route::get('/success', [App\Http\Controllers\Website\CheckoutController::class, 'success'])->name('success');
    Route::get('/cancel', [App\Http\Controllers\Website\CheckoutController::class, 'cancel'])->name('cancel');
    Route::post('/webhook', [App\Http\Controllers\Website\CheckoutController::class, 'webhook'])->name('webhook');
});

// ==================== SUBSCRIPTION ROUTES ====================
Route::prefix('subscription')->name('subscription.')->middleware(['auth'])->group(function () {
    Route::get('/plans', [App\Http\Controllers\Website\SubscriptionController::class, 'plans'])->name('plans');
    Route::post('/subscribe', [App\Http\Controllers\Website\SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::post('/cancel', [App\Http\Controllers\Website\SubscriptionController::class, 'cancel'])->name('cancel');
    Route::post('/resume', [App\Http\Controllers\Website\SubscriptionController::class, 'resume'])->name('resume');
    Route::get('/invoice/{id}', [App\Http\Controllers\Website\SubscriptionController::class, 'invoice'])->name('invoice');
});

// ==================== ACCOUNT ROUTES ====================
Route::prefix('account')->name('account.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Website\AccountController::class, 'index'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Website\AccountController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Website\AccountController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [App\Http\Controllers\Website\AccountController::class, 'updatePassword'])->name('password.update');
    Route::get('/subscriptions', [App\Http\Controllers\Website\AccountController::class, 'subscriptions'])->name('subscriptions');
    Route::get('/invoices', [App\Http\Controllers\Website\AccountController::class, 'invoices'])->name('invoices');
    Route::get('/settings', [App\Http\Controllers\Website\AccountController::class, 'settings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Website\AccountController::class, 'updateSettings'])->name('settings.update');
});

// ==================== DEMO ROUTE ====================
Route::get('/demo', [HomeController::class, 'demo'])->name('demo');

// ==================== FALLBACK ROUTE ====================
// Catch-all route for dynamic pages (should be last)
Route::fallback(function () {
    return app(App\Http\Controllers\Website\PageController::class)->fallback();
});