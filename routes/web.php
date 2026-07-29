<?php

use App\Http\Controllers\{ 
    HomeController, 
    AboutController, 
    ServicesController, 
    PortfolioController, 
    PricingController, 
    BlogController, 
    ResourcesController, 
    CareersController, 
    ContactController, 
    SitemapController, 
    SearchController 
};
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Website\NewsletterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/language/switch/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch');

// ============================================
// HOME
// ============================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ============================================
// PAGES ROUTES (Using 'pages.' prefix)
// ============================================
Route::prefix('pages')->name('pages.')->group(function () {
    // About Pages
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/about/mission', [AboutController::class, 'mission'])->name('about.mission');
    Route::get('/about/vision', [AboutController::class, 'vision'])->name('about.vision');
    Route::get('/about/history', [AboutController::class, 'history'])->name('about.history');
    Route::get('/about/team', [AboutController::class, 'team'])->name('about.team');
    Route::get('/about/values', [AboutController::class, 'values'])->name('about.values');
    
    // Resources Pages
    Route::get('/faq', [ResourcesController::class, 'faq'])->name('faq');
    Route::get('/documentation', [ResourcesController::class, 'documentation'])->name('documentation');
    Route::get('/case-studies', [ResourcesController::class, 'caseStudies'])->name('case-studies');
    Route::get('/downloads', [ResourcesController::class, 'downloads'])->name('downloads');
    
    // Careers
    Route::get('/careers', [CareersController::class, 'index'])->name('careers');
    Route::get('/careers/openings', [CareersController::class, 'openings'])->name('careers.openings');
    
    // Legal Pages
    Route::get('/privacy-policy', function () {
        return view('frontend.pages.privacy');
    })->name('privacy');
    
    Route::get('/terms-of-service', function () {
        return view('frontend.pages.terms');
    })->name('terms');
    
    Route::get('/refund-policy', function () {
        return view('frontend.pages.refund');
    })->name('refund');
    
    Route::get('/cookie-policy', function () {
        return view('frontend.pages.cookie');
    })->name('cookie');
    
    // Sitemap
    Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap');
});

// ============================================
// ABOUT ROUTES (Alternative 'about.' prefix)
// ============================================
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/', [AboutController::class, 'index'])->name('index');
    Route::get('/mission', [AboutController::class, 'mission'])->name('mission');
    Route::get('/vision', [AboutController::class, 'vision'])->name('vision');
    Route::get('/history', [AboutController::class, 'history'])->name('history');
    Route::get('/team', [AboutController::class, 'team'])->name('team');
    Route::get('/values', [AboutController::class, 'values'])->name('values');
});

// ============================================
// SERVICES ROUTES
// ============================================
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServicesController::class, 'index'])->name('index');
    Route::get('/website-design', [ServicesController::class, 'webDesign'])->name('web-design');
    Route::get('/web-development', [ServicesController::class, 'webDevelopment'])->name('web-development');
    Route::get('/ecommerce', [ServicesController::class, 'ecommerce'])->name('ecommerce');
    Route::get('/seo', [ServicesController::class, 'seo'])->name('seo');
    Route::get('/uiux-design', [ServicesController::class, 'uiuxDesign'])->name('uiux-design');
    Route::get('/maintenance', [ServicesController::class, 'maintenance'])->name('maintenance');
    Route::get('/hosting', [ServicesController::class, 'hosting'])->name('hosting');
    Route::get('/{slug}', [ServicesController::class, 'show'])->name('show');
});

// ============================================
// PORTFOLIO ROUTES
// ============================================
Route::prefix('portfolio')->name('portfolio.')->group(function () {
    Route::get('/', [PortfolioController::class, 'index'])->name('index');
    Route::get('/category/{category}', [PortfolioController::class, 'category'])->name('category');
    Route::get('/{slug}', [PortfolioController::class, 'show'])->name('show');
});

// ============================================
// PRICING ROUTES
// ============================================
Route::prefix('pricing')->name('pricing.')->group(function () {
    Route::get('/', [PricingController::class, 'index'])->name('index');
    Route::get('/compare', [PricingController::class, 'compare'])->name('compare');
    Route::get('/calculator', [PricingController::class, 'calculator'])->name('calculator');
});

// ============================================
// BLOG ROUTES
// ============================================
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
    Route::get('/category/{category}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/author/{author}', [BlogController::class, 'author'])->name('author');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// ============================================
// RESOURCES ROUTES (Alternative 'resources.' prefix)
// ============================================
Route::prefix('resources')->name('resources.')->group(function () {
    Route::get('/faq', [ResourcesController::class, 'faq'])->name('faq');
    Route::get('/documentation', [ResourcesController::class, 'documentation'])->name('documentation');
    Route::get('/case-studies', [ResourcesController::class, 'caseStudies'])->name('case-studies');
    Route::get('/downloads', [ResourcesController::class, 'downloads'])->name('downloads');
});

// ============================================
// CAREERS ROUTES (Alternative 'careers.' prefix)
// ============================================
Route::prefix('careers')->name('careers.')->group(function () {
    Route::get('/', [CareersController::class, 'index'])->name('index');
    Route::get('/openings', [CareersController::class, 'openings'])->name('openings');
});

// ============================================
// CONTACT ROUTES
// ============================================
Route::prefix('contact')->name('contact.')->group(function () {
    Route::get('/', [ContactController::class, 'index'])->name('index');
    Route::get('/quote', [ContactController::class, 'quote'])->name('quote');
    Route::get('/book-meeting', [ContactController::class, 'bookMeeting'])->name('book-meeting');
    Route::post('/submit', [ContactController::class, 'submit'])->name('submit');
    Route::post('/quote/submit', [ContactController::class, 'submitQuote'])->name('quote.submit');
    Route::post('/book-meeting/submit', [ContactController::class, 'submitMeeting'])->name('book-meeting.submit');
    Route::get('/success', [ContactController::class, 'success'])->name('success');
});

// ============================================
// NEWSLETTER ROUTES
// ============================================
Route::prefix('api')->name('api.')->group(function () {
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
        ->name('newsletter.subscribe');
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])
        ->name('newsletter.unsubscribe');
        
});

// ============================================
// LEGAL ROUTES (Alternative 'legal.' prefix)
// ============================================
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/privacy', function () {
        return view('frontend.pages.privacy');
    })->name('privacy');
    
    Route::get('/terms', function () {
        return view('frontend.pages.terms');
    })->name('terms');
    
    Route::get('/refund', function () {
        return view('frontend.pages.refund');
    })->name('refund');
    
    Route::get('/cookie', function () {
        return view('frontend.pages.cookie');
    })->name('cookie');
});

// ============================================
// SITEMAP
// ============================================
Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap.xml', [SitemapController::class, 'xml'])->name('sitemap.xml');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// ============================================
// SEARCH
// ============================================
// Search Routes
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
Route::get('/search/stats', [SearchController::class, 'stats'])->name('search.stats');

// ============================================
// AUTH ROUTES (Laravel Breeze/Jetstream)
// ============================================
Route::get('/dashboard', function () {
    return view('admin.dashboard.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// routes/web.php - For testing only
Route::get('/test-404', function () {
    abort(404);
});

Route::get('/test-403', function () {
    abort(403);
});

Route::get('/test-500', function () {
    abort(500);
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';