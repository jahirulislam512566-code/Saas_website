<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\LandingController;
use App\Http\Controllers\Website\PageController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\PricingController;
use App\Http\Controllers\Website\FeaturesController;
use App\Http\Controllers\Website\TemplatesController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\BlogController;
use App\Http\Controllers\Website\PortfolioController;
use App\Http\Controllers\Website\ServiceController;
use App\Http\Controllers\Website\SearchController;
use App\Http\Controllers\Website\NewsletterController;
use App\Http\Controllers\Website\AccountController;
use App\Http\Controllers\Website\SubscriptionController;
use App\Http\Controllers\Website\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// ==================== MAIN ROUTES ====================

// Home page - using controller
Route::get('/', [HomeController::class, 'index'])->name('home');

// ==================== LANDING PAGES ====================

Route::prefix('landing')->name('landing.')->group(function () {
    Route::get('/', [LandingController::class, 'index'])->name('home');
    Route::get('/pricing', [LandingController::class, 'pricing'])->name('pricing');
    Route::get('/features', [LandingController::class, 'features'])->name('features');
    Route::get('/templates', [LandingController::class, 'templates'])->name('templates');
    Route::get('/about', [LandingController::class, 'about'])->name('about');
    Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
    Route::post('/contact', [LandingController::class, 'storeContact'])->name('contact.store');
    Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
    Route::get('/testimonials', [LandingController::class, 'testimonials'])->name('testimonials');
});

// ==================== WEBSITE ROUTES ====================

Route::prefix('website')->name('website.')->group(function () {
    // Static Pages - FIXED: These were missing in your original file
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    
    // Authentication Routes - ADD THIS SECTION
    // These are needed for the login/register links in your header
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    // Services
    Route::get('/services', [ServiceController::class, 'index'])->name('services');
    Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
    
    // Blog
    Route::get('/blog', [BlogController::class, 'index'])->name('blog');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');
    Route::get('/blog/author/{id}', [BlogController::class, 'author'])->name('blog.author');
    Route::post('/blog/{post}/comment', [BlogController::class, 'storeComment'])->name('blog.comment.store');
    
    // Portfolio
    Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
    Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
    Route::get('/portfolio/category/{slug}', [PortfolioController::class, 'category'])->name('portfolio.category');
    
    // Pricing
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');
    Route::post('/pricing/subscribe', [PricingController::class, 'subscribe'])->name('pricing.subscribe');
    
    // Features
    Route::get('/features', [FeaturesController::class, 'index'])->name('features');
    Route::get('/features/{slug}', [FeaturesController::class, 'show'])->name('features.show');
    
    // Templates
    Route::get('/templates', [TemplatesController::class, 'index'])->name('templates');
    Route::get('/templates/{slug}', [TemplatesController::class, 'show'])->name('templates.show');
    Route::post('/templates/preview', [TemplatesController::class, 'preview'])->name('templates.preview');
    
    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
    
    // Newsletter
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
    Route::get('/newsletter/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
    Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
    
    // Account Routes - FIXED: Moved to proper auth middleware
    Route::middleware(['auth'])->group(function () {
        Route::get('/account', [AccountController::class, 'index'])->name('account.dashboard');
        Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
        Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
        Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
        Route::get('/account/subscriptions', [AccountController::class, 'subscriptions'])->name('account.subscriptions');
        Route::get('/account/invoices', [AccountController::class, 'invoices'])->name('account.invoices');
        Route::get('/account/settings', [AccountController::class, 'settings'])->name('account.settings');
        Route::put('/account/settings', [AccountController::class, 'updateSettings'])->name('account.settings.update');
    });
    
    // Dynamic Pages (must be last)
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page');
    
    // Sitemap
    Route::get('/sitemap.xml', function () {
        return response()->view('website.sitemap')->header('Content-Type', 'application/xml');
    })->name('sitemap');
    
    // Robots.txt
    Route::get('/robots.txt', function () {
        return response()->view('website.robots')->header('Content-Type', 'text/plain');
    })->name('robots');
});

// ==================== DASHBOARD ROUTES ====================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==================== USER PROFILE ROUTES ====================

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== SUBSCRIPTION ROUTES ====================

Route::prefix('subscription')->name('subscription.')->middleware(['auth'])->group(function () {
    Route::get('/plans', [SubscriptionController::class, 'plans'])->name('plans');
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe');
    Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    Route::post('/resume', [SubscriptionController::class, 'resume'])->name('resume');
    Route::get('/invoice/{id}', [SubscriptionController::class, 'invoice'])->name('invoice');
    Route::get('/payment-methods', [SubscriptionController::class, 'paymentMethods'])->name('payment.methods');
    Route::post('/payment-methods', [SubscriptionController::class, 'addPaymentMethod'])->name('payment.methods.add');
    Route::delete('/payment-methods/{id}', [SubscriptionController::class, 'removePaymentMethod'])->name('payment.methods.remove');
});

// ==================== CHECKOUT ROUTES ====================

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/{plan}', [CheckoutController::class, 'index'])->name('index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('process');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
    Route::post('/webhook', [CheckoutController::class, 'webhook'])->name('webhook');
});

// ==================== FALLBACK ROUTE ====================
// Catch-all route for 404 pages (must be last)
Route::fallback(function () {
    return view('errors.404');
});

// ==================== INCLUDE AUTH ROUTES ====================
require __DIR__.'/auth.php';

// ==================== INCLUDE ADMIN ROUTES ====================
require __DIR__.'/admin.php';