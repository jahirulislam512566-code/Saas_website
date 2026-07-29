<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    /**
     * Display search results page.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $perPage = $request->get('per_page', 12);
        
        if (empty($query) || strlen($query) < 2) {
            return view('frontend.search.results', [
                'query' => $query,
                'results' => collect(),
                'type' => $type,
                'totalResults' => 0,
                'searchTime' => 0,
            ]);
        }

        $startTime = microtime(true);
        $results = $this->performSearch($query, $type, $perPage);
        $searchTime = round((microtime(true) - $startTime) * 1000, 2);

        return view('frontend.search.results', [
            'query' => $query,
            'results' => $results,
            'type' => $type,
            'totalResults' => $results->total(),
            'searchTime' => $searchTime,
        ]);
    }

    /**
     * Perform the actual search.
     *
     * @param string $query
     * @param string $type
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function performSearch($query, $type, $perPage)
    {
        $results = collect();

        switch ($type) {
            case 'blog':
                $results = $this->searchBlogPosts($query, $perPage);
                break;
            case 'services':
                $results = $this->searchServices($query, $perPage);
                break;
            case 'portfolio':
                $results = $this->searchPortfolio($query, $perPage);
                break;
            case 'team':
                $results = $this->searchTeam($query, $perPage);
                break;
            default:
                $results = $this->searchAll($query, $perPage);
                break;
        }

        return $results;
    }

    /**
     * Search blog posts.
     *
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function searchBlogPosts($query, $limit)
    {
        return BlogPost::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('author_name', 'like', "%{$query}%")
                  ->orWhereHas('category', function ($cat) use ($query) {
                      $cat->where('name', 'like', "%{$query}%");
                  });
            })
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search services.
     *
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function searchServices($query, $limit)
    {
        $queryBuilder = Service::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });

        if (Schema::hasColumn('services', 'order')) {
            $queryBuilder->orderBy('order', 'asc');
        }

        return $queryBuilder->limit($limit)->get();
    }

    /**
     * Search portfolio items.
     *
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function searchPortfolio($query, $limit)
    {
        $queryBuilder = Portfolio::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
                
                if (Schema::hasColumn('portfolios', 'client')) {
                    $q->orWhere('client', 'like', "%{$query}%");
                }
                
                if (Schema::hasColumn('portfolios', 'technologies')) {
                    $q->orWhere('technologies', 'like', "%{$query}%");
                }
            })
            ->with(['category']);

        if (Schema::hasColumn('portfolios', 'order')) {
            $queryBuilder->orderBy('order', 'asc');
        } else {
            $queryBuilder->orderBy('created_at', 'desc');
        }

        return $queryBuilder->limit($limit)->get();
    }

    /**
     * Search team members (users) and teams.
     *
     * @param string $query
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function searchTeam($query, $limit)
    {
        $results = collect();

        // Search in teams table
        if (Schema::hasTable('teams')) {
            $teams = Team::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->limit($limit)
                ->get()
                ->map(function ($team) {
                    $team->type = 'team';
                    $team->type_label = 'Team';
                    $team->icon = 'fa-users';
                    $team->url = route('pages.about.team');
                    $team->title = $team->name;
                    return $team;
                });
            
            $results = $results->merge($teams);
        }

        // Search in users table - only use columns that actually exist
        if (Schema::hasTable('users')) {
            $userQuery = User::query();
            
            // Build where clause with only existing columns
            $userQuery->where(function ($q) use ($query) {
                // Name column (exists)
                if (Schema::hasColumn('users', 'name')) {
                    $q->orWhere('name', 'like', "%{$query}%");
                }
                
                // Email column (exists)
                if (Schema::hasColumn('users', 'email')) {
                    $q->orWhere('email', 'like', "%{$query}%");
                }
                
                // Company name column (exists)
                if (Schema::hasColumn('users', 'company_name')) {
                    $q->orWhere('company_name', 'like', "%{$query}%");
                }
                
                // Job title column (exists)
                if (Schema::hasColumn('users', 'job_title')) {
                    $q->orWhere('job_title', 'like', "%{$query}%");
                }
                
                // Phone column (exists)
                if (Schema::hasColumn('users', 'phone')) {
                    $q->orWhere('phone', 'like', "%{$query}%");
                }
            });
            
            // Only get active users
            if (Schema::hasColumn('users', 'is_active')) {
                $userQuery->where('is_active', true);
            }
            
            $users = $userQuery->limit($limit)->get()->map(function ($user) {
                $user->type = 'member';
                $user->type_label = 'Team Member';
                $user->icon = 'fa-user';
                $user->url = route('pages.about.team');
                $user->title = $user->name;
                return $user;
            });
            
            $results = $results->merge($users);
        }

        return $results->unique('id')->values();
    }

    /**
     * Search all content types.
     *
     * @param string $query
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function searchAll($query, $perPage)
    {
        $blogPosts = $this->searchBlogPosts($query, 999);
        $services = $this->searchServices($query, 999);
        $portfolio = $this->searchPortfolio($query, 999);
        $teamResults = $this->searchTeam($query, 999);

        $allResults = collect();

        // Add blog posts with type
        foreach ($blogPosts as $item) {
            $item->type = 'blog';
            $item->type_label = 'Blog Post';
            $item->icon = 'fa-newspaper';
            $item->url = route('blog.show', $item->slug);
            $allResults->push($item);
        }

        // Add services with type
        foreach ($services as $item) {
            $item->type = 'service';
            $item->type_label = 'Service';
            $item->icon = 'fa-bolt';
            $item->url = route('services.show', $item->slug);
            $allResults->push($item);
        }

        // Add portfolio with type
        foreach ($portfolio as $item) {
            $item->type = 'portfolio';
            $item->type_label = 'Portfolio Project';
            $item->icon = 'fa-briefcase';
            $item->url = route('portfolio.show', $item->slug);
            $allResults->push($item);
        }

        // Add team results
        foreach ($teamResults as $item) {
            $allResults->push($item);
        }

        // Sort by relevance
        $allResults = $allResults->sortByDesc(function ($item) use ($query) {
            $relevance = 0;
            $title = $item->title ?? $item->name ?? '';
            if (stripos($title, $query) !== false) {
                $relevance += 10;
            }
            if (isset($item->description) && stripos($item->description, $query) !== false) {
                $relevance += 5;
            }
            if (isset($item->created_at)) {
                $relevance += $item->created_at->diffInDays(now()) * 0.1;
            }
            return $relevance;
        });

        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $paginated = $allResults->slice($offset, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $paginated,
            $allResults->count(),
            $perPage,
            $page,
            ['path' => route('search'), 'query' => ['q' => request()->get('q')]]
        );
    }

    /**
     * API endpoint for search suggestions (AJAX).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        $results = collect();
        
        // Search Blog Posts
        $blogPosts = BlogPost::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => 'blog',
                    'url' => route('blog.show', $item->slug),
                    'icon' => 'fa-newspaper',
                    'category' => 'Blog',
                ];
            });

        $results = $results->merge($blogPosts);

        // Search Services
        $services = Service::where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->name,
                    'type' => 'service',
                    'url' => route('services.show', $item->slug),
                    'icon' => 'fa-bolt',
                    'category' => 'Service',
                ];
            });

        $results = $results->merge($services);

        // Search Portfolio
        $portfolio = Portfolio::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
                
                if (Schema::hasColumn('portfolios', 'client')) {
                    $q->orWhere('client', 'like', "%{$query}%");
                }
            })
            ->limit(3)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'type' => 'portfolio',
                    'url' => route('portfolio.show', $item->slug),
                    'icon' => 'fa-briefcase',
                    'category' => 'Portfolio',
                ];
            });

        $results = $results->merge($portfolio);

        // Search Teams
        if (Schema::hasTable('teams')) {
            $teams = Team::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->limit(2)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->name,
                        'type' => 'team',
                        'url' => route('pages.about.team'),
                        'icon' => 'fa-users',
                        'category' => 'Team',
                    ];
                });

            $results = $results->merge($teams);
        }

        // Search Users - only use columns that exist
        if (Schema::hasTable('users')) {
            $userQuery = User::query();
            
            $userQuery->where(function ($q) use ($query) {
                if (Schema::hasColumn('users', 'name')) {
                    $q->orWhere('name', 'like', "%{$query}%");
                }
                if (Schema::hasColumn('users', 'email')) {
                    $q->orWhere('email', 'like', "%{$query}%");
                }
                if (Schema::hasColumn('users', 'company_name')) {
                    $q->orWhere('company_name', 'like', "%{$query}%");
                }
                if (Schema::hasColumn('users', 'job_title')) {
                    $q->orWhere('job_title', 'like', "%{$query}%");
                }
            });
            
            if (Schema::hasColumn('users', 'is_active')) {
                $userQuery->where('is_active', true);
            }
            
            $users = $userQuery->limit(2)->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->name,
                    'type' => 'member',
                    'url' => route('pages.about.team'),
                    'icon' => 'fa-user',
                    'category' => 'Team Member',
                ];
            });

            $results = $results->merge($users);
        }

        return response()->json($results->values());
    }

    /**
     * Advanced search with filters.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function advanced(Request $request)
    {
        $query = $request->get('q', '');
        $filters = [
            'category' => $request->get('category'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'sort' => $request->get('sort', 'relevance'),
        ];

        $validator = Validator::make($filters, [
            'category' => 'nullable|string|max:255',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after:date_from',
            'sort' => 'nullable|in:relevance,newest,oldest',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $results = $this->performAdvancedSearch($query, $filters);

        return view('frontend.search.advanced', [
            'query' => $query,
            'results' => $results,
            'filters' => $filters,
        ]);
    }

    /**
     * Perform advanced search with filters.
     *
     * @param string $query
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function performAdvancedSearch($query, $filters)
    {
        $search = BlogPost::where('is_published', true);

        if (!empty($query)) {
            $search->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            });
        }

        if (!empty($filters['category'])) {
            $search->whereHas('category', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        if (!empty($filters['date_from'])) {
            $search->whereDate('published_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $search->whereDate('published_at', '<=', $filters['date_to']);
        }

        switch ($filters['sort']) {
            case 'newest':
                $search->orderBy('published_at', 'desc');
                break;
            case 'oldest':
                $search->orderBy('published_at', 'asc');
                break;
            case 'relevance':
            default:
                $search->orderBy('views', 'desc');
                break;
        }

        return $search->paginate(12);
    }

    /**
     * Get search statistics.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([
                'total' => 0,
                'by_type' => []
            ]);
        }

        $stats = [
            'blog_posts' => BlogPost::where('is_published', true)
                ->where('title', 'like', "%{$query}%")
                ->count(),
            'services' => Service::where('is_active', true)
                ->where('name', 'like', "%{$query}%")
                ->count(),
            'portfolio' => Portfolio::where('is_published', true)
                ->where('title', 'like', "%{$query}%")
                ->count(),
        ];

        return response()->json([
            'total' => array_sum($stats),
            'by_type' => $stats,
        ]);
    }
}