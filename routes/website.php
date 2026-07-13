<?php

use App\Http\Controllers\Website\{
    HomeController,
    AboutController,
    ServiceController,
    ContactController,
    BlogController,
    PortfolioController,
    TeamController,
    PricingController,
    FaqController,
    PageController,
    SearchController,
    NewsletterController,
    AuthController
};
use App\Http\Controllers\Website\ThemeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application's frontend.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

// ==================== MAIN HOME PAGE ====================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.redirect');

// ==================== WEBSITE PAGES ====================
Route::prefix('website')->name('website.')->group(function () {
    
    // Static Pages
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    
    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
    
    // Blog
    Route::get('/blog', [BlogController::class, 'index'])->name('blog');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
    Route::post('/blog/{post}/comment', [BlogController::class, 'storeComment'])->name('blog.comment.store');
    
    // Portfolio
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
    Route::get('/portfolio/category/{slug}', [PortfolioController::class, 'category'])->name('portfolio.category');
    
    // Team
    Route::get('/team', [TeamController::class, 'index'])->name('team');
    Route::get('/team/{slug}', [TeamController::class, 'show'])->name('team.show');
    
    // Pricing
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
    
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
    
    // Theme-specific routes
    Route::prefix('themes')->name('themes.')->group(function () {
        // Business theme
        Route::prefix('business')->name('business.')->group(function () {
            Route::get('/', [ThemeController::class, 'business'])->name('home');
            Route::get('/about', [ThemeController::class, 'businessAbout'])->name('about');
            Route::get('/services', [ThemeController::class, 'businessServices'])->name('services');
            Route::get('/contact', [ThemeController::class, 'businessContact'])->name('contact');
        });
        
        // Modern theme
        Route::prefix('modern')->name('modern.')->group(function () {
            Route::get('/', [ThemeController::class, 'modern'])->name('home');
            Route::get('/about', [ThemeController::class, 'modernAbout'])->name('about');
            Route::get('/services', [ThemeController::class, 'modernServices'])->name('services');
            Route::get('/contact', [ThemeController::class, 'modernContact'])->name('contact');
        });
        
        // Creative theme
        Route::prefix('creative')->name('creative.')->group(function () {
            Route::get('/', [ThemeController::class, 'creative'])->name('home');
            Route::get('/about', [ThemeController::class, 'creativeAbout'])->name('about');
            Route::get('/services', [ThemeController::class, 'creativeServices'])->name('services');
            Route::get('/contact', [ThemeController::class, 'creativeContact'])->name('contact');
        });
    });
    
    // Sitemap
    Route::get('/sitemap', function () {
        return view('website.sitemap');
    })->name('sitemap');
    
    // Robots.txt
    Route::get('/robots.txt', function () {
        return response()->view('website.robots')->header('Content-Type', 'text/plain');
    })->name('robots');
});

// ==================== AUTHENTICATION ROUTES (Optional) ====================
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])->name('verification.resend');
    Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
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

// ==================== FALLBACK ROUTE ====================
// Catch-all route for dynamic pages (should be last)
Route::fallback(function () {
    return app(App\Http\Controllers\Website\PageController::class)->fallback();
});