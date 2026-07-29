<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\WebsiteBuilder\Block;
use App\Models\WebsiteBuilder\BlockCategory;
use App\Models\WebsiteBuilder\Theme;
use App\Models\WebsiteBuilder\Template;
use App\Models\WebsiteBuilder\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WebsiteBuilderController extends Controller
{
    // Dashboard
    public function index()
    {
        $stats = [
            'total_websites' => Website::count(),
            'blocks' => Block::count(),
            'templates' => Template::count(),
            'components' => Block::where('type', 'component')->count(),
            'themes' => Theme::count()
        ];
        
        $recentWebsites = Website::orderBy('updated_at', 'desc')->limit(5)->get();
        $popularTemplates = Template::orderBy('downloads', 'desc')->limit(5)->get();
        
        return view('admin.website-builder.index', compact('stats', 'recentWebsites', 'popularTemplates'));
    }
    
    // Blocks
    public function blocks()
    {
        $blocks = Block::with('category')->paginate(20);
        $categories = BlockCategory::all();
        
        return view('admin.website-builder.blocks', compact('blocks', 'categories'));
    }
    
    public function storeBlock(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:block_categories,id',
            'type' => 'required|in:section,component,layout',
            'preview' => 'nullable|url',
            'content' => 'required|string',
            'css' => 'nullable|string',
            'js' => 'nullable|string',
            'is_global' => 'boolean',
            'is_active' => 'boolean'
        ]);
        
        $block = Block::create($validated);
        
        return response()->json(['success' => true, 'block' => $block]);
    }
    
    public function updateBlock(Request $request, Block $block)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:block_categories,id',
            'type' => 'required|in:section,component,layout',
            'preview' => 'nullable|url',
            'content' => 'required|string',
            'css' => 'nullable|string',
            'js' => 'nullable|string',
            'is_global' => 'boolean',
            'is_active' => 'boolean'
        ]);
        
        $block->update($validated);
        
        return response()->json(['success' => true]);
    }
    
    public function destroyBlock(Block $block)
    {
        $block->delete();
        return response()->json(['success' => true]);
    }
    
    // Builder
    public function builder(Request $request, $websiteId = null)
    {
        $website = $websiteId ? Website::findOrFail($websiteId) : null;
        $components = Block::where('type', 'component')->where('is_active', true)->get();
        $page = $website ? $website->pages()->where('is_home', true)->first() : null;
        
        return view('admin.website-builder.builder', compact('website', 'components', 'page'));
    }
    
    public function saveBuilder(Request $request, $websiteId)
    {
        $website = Website::findOrFail($websiteId);
        $page = $website->pages()->where('is_home', true)->firstOrCreate(['is_home' => true]);
        
        $page->update([
            'elements' => $request->elements
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function previewBuilder($websiteId)
    {
        $website = Website::findOrFail($websiteId);
        $page = $website->pages()->where('is_home', true)->first();
        
        return view('admin.website-builder.preview', compact('website', 'page'));
    }
    
    public function publishBuilder($websiteId)
    {
        $website = Website::findOrFail($websiteId);
        $website->update(['status' => 'published']);
        
        // Generate static files
        $this->generateStaticFiles($website);
        
        return response()->json(['success' => true]);
    }
    
    // Themes
    public function themes()
    {
        $themes = Theme::all();
        $globalStyles = Theme::getGlobalStyles();
        
        $stats = [
            'themes' => Theme::count(),
            'active' => Theme::where('status', 'active')->count(),
            'templates' => Template::count(),
            'styles' => count($globalStyles)
        ];
        
        return view('admin.website-builder.themes', compact('themes', 'globalStyles', 'stats'));
    }
    
    public function storeStyles(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => 'nullable|string',
            'secondary_color' => 'nullable|string',
            'font_family' => 'nullable|string',
            'border_radius' => 'nullable|string'
        ]);
        
        Theme::updateGlobalStyles($validated);
        
        return response()->json(['success' => true]);
    }
    
    public function resetStyles()
    {
        $defaultStyles = Theme::getDefaultStyles();
        Theme::updateGlobalStyles($defaultStyles);
        
        return response()->json(['success' => true, 'styles' => $defaultStyles]);
    }
    
    // Settings
    public function settings()
    {
        $settings = config('website-builder.settings', []);
        $themes = Theme::all();
        $templates = Template::all();
        
        return view('admin.website-builder.settings', compact('settings', 'themes', 'templates'));
    }
    
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'default_theme' => 'nullable|exists:themes,id',
            'default_template' => 'nullable|exists:templates,id',
            'default_font' => 'nullable|string',
            'enable_drag_drop' => 'boolean',
            'enable_live_preview' => 'boolean',
            'enable_ai_assistant' => 'boolean',
            'enable_code_export' => 'boolean',
            'enable_custom_css' => 'boolean',
            'enable_custom_js' => 'boolean',
            'enable_animation' => 'boolean',
            'enable_responsive' => 'boolean',
            'export_format' => 'nullable|string',
            'minify_output' => 'nullable|string',
            'include_assets' => 'boolean',
            'include_meta' => 'boolean',
            'include_seo' => 'boolean',
            'include_analytics' => 'boolean'
        ]);
        
        config(['website-builder.settings' => $validated]);
        
        return response()->json(['success' => true]);
    }
    
    public function resetSettings()
    {
        $defaultSettings = config('website-builder.defaults', []);
        config(['website-builder.settings' => $defaultSettings]);
        
        return response()->json(['success' => true, 'settings' => $defaultSettings]);
    }
    
    // Helper Methods
    private function generateStaticFiles($website)
    {
        // Generate HTML, CSS, JS files
        // This would be the actual static site generation logic
    }
}