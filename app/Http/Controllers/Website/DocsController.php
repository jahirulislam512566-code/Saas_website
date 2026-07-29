<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocsController extends Controller
{
    /**
     * Display the documentation index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = $this->getCategories();
        $recentArticles = $this->getRecentArticles();
        $popularArticles = $this->getPopularArticles();
        
        return view('website.docs.index', compact('categories', 'recentArticles', 'popularArticles'));
    }

    /**
     * Display a specific documentation category.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function category(string $slug)
    {
        $categories = $this->getCategories();
        $category = collect($categories)->firstWhere('slug', $slug);
        
        if (!$category) {
            abort(404, 'Category not found.');
        }
        
        return view('website.docs.category', compact('category', 'categories'));
    }

    /**
     * Display a specific documentation article.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function article(string $slug)
    {
        $article = $this->getArticle($slug);
        
        if (!$article) {
            abort(404, 'Article not found.');
        }
        
        $categories = $this->getCategories();
        $relatedArticles = $this->getRelatedArticles($slug);
        $previousNext = $this->getPreviousNextArticles($slug);
        
        return view('website.docs.article', compact(
            'article', 
            'categories', 
            'relatedArticles', 
            'previousNext'
        ));
    }

    /**
     * Search documentation.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $results = [];
        
        if (!empty($query)) {
            $results = $this->searchArticles($query);
        }
        
        return view('website.docs.search', compact('query', 'results'));
    }

    /**
     * Get documentation categories.
     *
     * @return array
     */
    protected function getCategories()
    {
        return [
            [
                'name' => 'Getting Started',
                'slug' => 'getting-started',
                'description' => 'Learn the basics and get up and running quickly.',
                'icon' => 'fa-rocket',
                'order' => 1,
                'articles' => [
                    [
                        'title' => 'Quick Start Guide',
                        'slug' => 'quick-start',
                        'description' => 'Get started with our platform in 5 minutes',
                        'difficulty' => 'Beginner',
                        'read_time' => '5 min read',
                        'updated_at' => '2024-01-15',
                        'featured' => true
                    ],
                    [
                        'title' => 'Installation & Setup',
                        'slug' => 'installation',
                        'description' => 'Install our SDKs and configure your environment',
                        'difficulty' => 'Beginner',
                        'read_time' => '10 min read',
                        'updated_at' => '2024-01-20',
                        'featured' => false
                    ],
                    [
                        'title' => 'Authentication Guide',
                        'slug' => 'authentication',
                        'description' => 'Secure your application with API keys and OAuth',
                        'difficulty' => 'Intermediate',
                        'read_time' => '8 min read',
                        'updated_at' => '2024-01-18',
                        'featured' => false
                    ],
                    [
                        'title' => 'Your First Project',
                        'slug' => 'first-project',
                        'description' => 'Create and deploy your first project',
                        'difficulty' => 'Beginner',
                        'read_time' => '12 min read',
                        'updated_at' => '2024-01-22',
                        'featured' => true
                    ]
                ]
            ],
            [
                'name' => 'Guides',
                'slug' => 'guides',
                'description' => 'In-depth guides to help you master the platform.',
                'icon' => 'fa-book',
                'order' => 2,
                'articles' => [
                    [
                        'title' => 'Building Your First Application',
                        'slug' => 'building-first-app',
                        'description' => 'Step-by-step tutorial to build a complete application',
                        'difficulty' => 'Intermediate',
                        'read_time' => '20 min read',
                        'updated_at' => '2024-01-25',
                        'featured' => true
                    ],
                    [
                        'title' => 'Advanced Configuration',
                        'slug' => 'advanced-config',
                        'description' => 'Fine-tune your application with advanced settings',
                        'difficulty' => 'Advanced',
                        'read_time' => '15 min read',
                        'updated_at' => '2024-01-23',
                        'featured' => false
                    ],
                    [
                        'title' => 'Performance Optimization',
                        'slug' => 'performance-optimization',
                        'description' => 'Optimize your application for maximum performance',
                        'difficulty' => 'Advanced',
                        'read_time' => '18 min read',
                        'updated_at' => '2024-01-21',
                        'featured' => false
                    ],
                    [
                        'title' => 'Security Best Practices',
                        'slug' => 'security-best-practices',
                        'description' => 'Keep your application secure with our best practices',
                        'difficulty' => 'Intermediate',
                        'read_time' => '12 min read',
                        'updated_at' => '2024-01-19',
                        'featured' => false
                    ],
                    [
                        'title' => 'Database Management',
                        'slug' => 'database-management',
                        'description' => 'Manage your databases efficiently',
                        'difficulty' => 'Intermediate',
                        'read_time' => '15 min read',
                        'updated_at' => '2024-01-17',
                        'featured' => false
                    ],
                    [
                        'title' => 'Webhooks & Events',
                        'slug' => 'webhooks-events',
                        'description' => 'Build reactive applications with webhooks',
                        'difficulty' => 'Advanced',
                        'read_time' => '14 min read',
                        'updated_at' => '2024-01-16',
                        'featured' => false
                    ]
                ]
            ],
            [
                'name' => 'API Reference',
                'slug' => 'api-reference',
                'description' => 'Complete API reference for developers.',
                'icon' => 'fa-code',
                'order' => 3,
                'articles' => [
                    [
                        'title' => 'Authentication & Authorization',
                        'slug' => 'api-auth',
                        'description' => 'API authentication methods and best practices',
                        'difficulty' => 'Intermediate',
                        'read_time' => '10 min read',
                        'updated_at' => '2024-01-24',
                        'featured' => true
                    ],
                    [
                        'title' => 'Users API',
                        'slug' => 'api-users',
                        'description' => 'Manage users with our RESTful API',
                        'difficulty' => 'Beginner',
                        'read_time' => '12 min read',
                        'updated_at' => '2024-01-22',
                        'featured' => false
                    ],
                    [
                        'title' => 'Products API',
                        'slug' => 'api-products',
                        'description' => 'Create and manage products programmatically',
                        'difficulty' => 'Intermediate',
                        'read_time' => '14 min read',
                        'updated_at' => '2024-01-20',
                        'featured' => false
                    ],
                    [
                        'title' => 'Orders API',
                        'slug' => 'api-orders',
                        'description' => 'Process orders and manage transactions',
                        'difficulty' => 'Intermediate',
                        'read_time' => '16 min read',
                        'updated_at' => '2024-01-18',
                        'featured' => false
                    ],
                    [
                        'title' => 'Webhooks API',
                        'slug' => 'api-webhooks',
                        'description' => 'Set up and manage webhook endpoints',
                        'difficulty' => 'Advanced',
                        'read_time' => '13 min read',
                        'updated_at' => '2024-01-16',
                        'featured' => false
                    ],
                    [
                        'title' => 'Rate Limiting & Usage',
                        'slug' => 'api-rate-limiting',
                        'description' => 'Understand API rate limits and usage tiers',
                        'difficulty' => 'Beginner',
                        'read_time' => '8 min read',
                        'updated_at' => '2024-01-14',
                        'featured' => false
                    ]
                ]
            ],
            [
                'name' => 'FAQ',
                'slug' => 'faq',
                'description' => 'Frequently asked questions and answers.',
                'icon' => 'fa-question-circle',
                'order' => 4,
                'articles' => [
                    [
                        'title' => 'General Questions',
                        'slug' => 'faq-general',
                        'description' => 'Common questions about our platform',
                        'difficulty' => 'Beginner',
                        'read_time' => '5 min read',
                        'updated_at' => '2024-01-25',
                        'featured' => false
                    ],
                    [
                        'title' => 'Pricing & Billing',
                        'slug' => 'faq-pricing',
                        'description' => 'Questions about pricing plans and billing',
                        'difficulty' => 'Beginner',
                        'read_time' => '6 min read',
                        'updated_at' => '2024-01-24',
                        'featured' => false
                    ],
                    [
                        'title' => 'Technical Support',
                        'slug' => 'faq-technical',
                        'description' => 'Technical questions and troubleshooting',
                        'difficulty' => 'Intermediate',
                        'read_time' => '8 min read',
                        'updated_at' => '2024-01-23',
                        'featured' => false
                    ],
                    [
                        'title' => 'Security & Compliance',
                        'slug' => 'faq-security',
                        'description' => 'Questions about security and compliance',
                        'difficulty' => 'Intermediate',
                        'read_time' => '7 min read',
                        'updated_at' => '2024-01-22',
                        'featured' => false
                    ]
                ]
            ]
        ];
    }

    /**
     * Get a specific article by slug.
     *
     * @param string $slug
     * @return array|null
     */
    protected function getArticle(string $slug)
    {
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            foreach ($category['articles'] as $article) {
                if ($article['slug'] === $slug) {
                    // Enhance article with category information
                    $article['category'] = $category['name'];
                    $article['category_slug'] = $category['slug'];
                    
                    // Add Table of Contents
                    $article['toc'] = $this->generateToc($slug);
                    
                    // Add content (in real app, this would come from database or markdown files)
                    $article['content'] = $this->getArticleContent($slug);
                    
                    // Add related articles
                    $article['related_articles'] = $this->getRelatedArticles($slug);
                    
                    return $article;
                }
            }
        }
        
        return null;
    }

    /**
     * Get article content (simulated - in production, this would come from database/markdown).
     *
     * @param string $slug
     * @return string
     */
    protected function getArticleContent(string $slug)
    {
        // In a real application, you would fetch this from a database or markdown files
        $contents = [
            'quick-start' => $this->getQuickStartContent(),
            'installation' => $this->getInstallationContent(),
            'authentication' => $this->getAuthenticationContent(),
            // Add more content for other articles
        ];
        
        return $contents[$slug] ?? '<h2>Article Content</h2><p>This article is being written. Please check back soon!</p>';
    }

    /**
     * Generate Table of Contents for an article.
     *
     * @param string $slug
     * @return array
     */
    protected function generateToc(string $slug)
    {
        // In production, you would parse the content for headings
        $tocs = [
            'quick-start' => ['Introduction', 'Prerequisites', 'Installation', 'Configuration', 'Next Steps'],
            'installation' => ['System Requirements', 'Installation Methods', 'Configuration', 'Verification'],
            'authentication' => ['API Keys', 'OAuth Setup', 'Token Management', 'Best Practices'],
        ];
        
        return $tocs[$slug] ?? ['Overview', 'Getting Started', 'Configuration', 'Examples'];
    }

    /**
     * Get related articles.
     *
     * @param string $slug
     * @return array
     */
    protected function getRelatedArticles(string $slug)
    {
        $allArticles = [];
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            foreach ($category['articles'] as $article) {
                if ($article['slug'] !== $slug) {
                    $allArticles[] = $article;
                }
            }
        }
        
        // Return 3 random articles as related
        shuffle($allArticles);
        return array_slice($allArticles, 0, 3);
    }

    /**
     * Get previous and next articles.
     *
     * @param string $slug
     * @return array
     */
    protected function getPreviousNextArticles(string $slug)
    {
        $allArticles = [];
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            foreach ($category['articles'] as $article) {
                $allArticles[] = $article;
            }
        }
        
        $index = array_search($slug, array_column($allArticles, 'slug'));
        
        return [
            'previous' => $index > 0 ? $allArticles[$index - 1] : null,
            'next' => $index < count($allArticles) - 1 ? $allArticles[$index + 1] : null,
        ];
    }

    /**
     * Search articles.
     *
     * @param string $query
     * @return array
     */
    protected function searchArticles(string $query)
    {
        $results = [];
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            foreach ($category['articles'] as $article) {
                if (stripos($article['title'], $query) !== false || 
                    stripos($article['description'] ?? '', $query) !== false) {
                    $article['category'] = $category['name'];
                    $results[] = $article;
                }
            }
        }
        
        return $results;
    }

    /**
     * Get recent articles.
     *
     * @return array
     */
    protected function getRecentArticles()
    {
        $allArticles = [];
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            foreach ($category['articles'] as $article) {
                $article['category'] = $category['name'];
                $allArticles[] = $article;
            }
        }
        
        usort($allArticles, function($a, $b) {
            return strtotime($b['updated_at']) - strtotime($a['updated_at']);
        });
        
        return array_slice($allArticles, 0, 5);
    }

    /**
     * Get popular articles.
     *
     * @return array
     */
    protected function getPopularArticles()
    {
        // In production, you would track views/popularity
        $allArticles = [];
        $categories = $this->getCategories();
        
        foreach ($categories as $category) {
            foreach ($category['articles'] as $article) {
                if (isset($article['featured']) && $article['featured']) {
                    $article['category'] = $category['name'];
                    $allArticles[] = $article;
                }
            }
        }
        
        return array_slice($allArticles, 0, 4);
    }

    /**
     * Handle documentation feedback.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function feedback(Request $request)
    {
        $validated = $request->validate([
            'article' => 'required|string',
            'helpful' => 'required|boolean'
        ]);
        
        // In production, save feedback to database
        // You could use a model like DocFeedback::create($validated);
        
        return response()->json(['message' => 'Thank you for your feedback!'], 200);
    }

    /**
     * Get content for Quick Start guide.
     *
     * @return string
     */
    protected function getQuickStartContent()
    {
        return <<<'HTML'
        <h2>Introduction</h2>
        <p>Welcome to our platform! This quick start guide will help you get up and running in just a few minutes.</p>
        
        <h2>Prerequisites</h2>
        <ul>
            <li>Basic understanding of web development</li>
            <li>A registered account on our platform</li>
            <li>Your API key (available in your dashboard)</li>
        </ul>
        
        <h2>Installation</h2>
        <p>Install our SDK using your preferred package manager:</p>
        <pre><code>npm install @saas-platform/sdk
        # or
        yarn add @saas-platform/sdk
        
        # For Python
        pip install saas-platform-sdk
        
        # For Ruby
        gem install saas-platform-sdk</code></pre>
        
        <h2>Configuration</h2>
        <p>Configure the SDK with your API key:</p>
        <pre><code>import { SaaSPlaform } from '@saas-platform/sdk';
        
        const client = new SaaSPlaform({
            apiKey: 'your-api-key-here',
            environment: 'production' // or 'sandbox'
        });</code></pre>
        
        <h2>Your First API Call</h2>
        <p>Make your first API call to verify everything is working:</p>
        <pre><code>// Get user information
        const user = await client.users.getCurrent();
        console.log('Welcome, ' + user.name);
        
        // Create a new resource
        const resource = await client.resources.create({
            name: 'My First Resource',
            type: 'documentation'
        });</code></pre>
        
        <h2>Next Steps</h2>
        <p>Now that you're set up, explore these resources:</p>
        <ul>
            <li><a href="/docs/guides/building-first-app">Building Your First Application</a></li>
            <li><a href="/docs/api-reference/api-auth">API Authentication Guide</a></li>
            <li><a href="/docs/guides/performance-optimization">Performance Optimization</a></li>
        </ul>
        HTML;
    }

    /**
     * Get content for Installation guide.
     *
     * @return string
     */
    protected function getInstallationContent()
    {
        return <<<'HTML'
        <h2>System Requirements</h2>
        <p>Before installing, ensure your system meets these requirements:</p>
        <ul>
            <li>Node.js 14.x or higher (for JavaScript/TypeScript)</li>
            <li>Python 3.8 or higher (for Python)</li>
            <li>Ruby 2.7 or higher (for Ruby)</li>
            <li>PHP 7.4 or higher (for PHP)</li>
            <li>A stable internet connection</li>
        </ul>
        
        <h2>Installation Methods</h2>
        
        <h3>NPM/Yarn (JavaScript/TypeScript)</h3>
        <pre><code>npm install @saas-platform/sdk
        # or
        yarn add @saas-platform/sdk</code></pre>
        
        <h3>Composer (PHP)</h3>
        <pre><code>composer require saas-platform/sdk</code></pre>
        
        <h3>Pip (Python)</h3>
        <pre><code>pip install saas-platform-sdk</code></pre>
        
        <h3>Gem (Ruby)</h3>
        <pre><code>gem install saas-platform-sdk</code></pre>
        
        <h2>Configuration</h2>
        <p>After installation, configure the SDK with your environment variables:</p>
        <pre><code># .env file
        SAAS_PLATFORM_API_KEY=your-api-key
        SAAS_PLATFORM_ENVIRONMENT=production</code></pre>
        
        <h2>Verification</h2>
        <p>Verify your installation by running a simple test:</p>
        <pre><code>// test.js
        const { SaaSPlaform } = require('@saas-platform/sdk');
        
        const client = new SaaSPlaform({
            apiKey: process.env.SAAS_PLATFORM_API_KEY
        });
        
        client.healthCheck().then(response => {
            console.log('✅ Installation successful!', response);
        }).catch(error => {
            console.error('❌ Installation failed:', error);
        });</code></pre>
        HTML;
    }

    /**
     * Get content for Authentication guide.
     *
     * @return string
     */
    protected function getAuthenticationContent()
    {
        return <<<'HTML'
        <h2>Authentication Methods</h2>
        <p>Our platform supports multiple authentication methods to suit your needs:</p>
        
        <h3>API Keys</h3>
        <p>API keys are the simplest way to authenticate. You can generate them from your dashboard.</p>
        <pre><code>const client = new SaaSPlaform({
            apiKey: 'your-api-key'
        });</code></pre>
        
        <h3>OAuth 2.0</h3>
        <p>For applications requiring user authorization, we support OAuth 2.0:</p>
        <pre><code>// Step 1: Redirect to authorization URL
        const authUrl = client.auth.getAuthorizationUrl({
            clientId: 'your-client-id',
            redirectUri: 'https://your-app.com/callback',
            scope: ['read', 'write']
        });
        
        // Step 2: Exchange code for token
        const token = await client.auth.exchangeCode({
            code: 'authorization-code',
            redirectUri: 'https://your-app.com/callback'
        });</code></pre>
        
        <h3>JWT Tokens</h3>
        <p>For server-to-server communication, you can use JWT tokens:</p>
        <pre><code>const token = jwt.sign(
            { 
                sub: 'service-account',
                exp: Math.floor(Date.now() / 1000) + 3600
            },
            'your-private-key',
            { algorithm: 'RS256' }
        );
        
        const client = new SaaSPlaform({
            token: token
        });</code></pre>
        
        <h2>Best Practices</h2>
        <ul>
            <li><strong>Never hardcode API keys</strong> - Always use environment variables</li>
            <li><strong>Rotate keys regularly</strong> - Generate new keys and revoke old ones</li>
            <li><strong>Use scoped tokens</strong> - Limit permissions to what's needed</li>
            <li><strong>Implement rate limiting</strong> - Protect against abuse</li>
            <li><strong>Monitor usage</strong> - Keep track of authentication attempts</li>
        </ul>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Important:</strong> Always use HTTPS in production to ensure your API keys are encrypted in transit.
                    </p>
                </div>
            </div>
        </div>
        HTML;
    }
}