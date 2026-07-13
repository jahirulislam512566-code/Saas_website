{{-- resources/views/admin/analytics/templates.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Template Analytics')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <a href="{{ route('admin.analytics.dashboard') }}" class="text-gray-500 hover:text-gray-700">Analytics</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Templates</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Template Analytics</h1>
            <p class="text-sm text-gray-500 mt-1">Track template performance and usage statistics</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2 bg-white rounded-lg shadow-sm px-3 py-2 border border-gray-200">
                <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                <select id="period" class="border-0 bg-transparent text-sm focus:ring-0">
                    <option value="today">Today</option>
                    <option value="week" selected>This Week</option>
                    <option value="month">This Month</option>
                    <option value="quarter">This Quarter</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            <button onclick="refreshData()" class="p-2 bg-white rounded-lg shadow-sm border border-gray-200 hover:bg-gray-50 transition-colors">
                <i class="fas fa-sync text-gray-500"></i>
            </button>
            <a href="{{ route('admin.analytics.export', 'templates') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-download mr-2"></i> Export
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Templates</p>
                    <p class="text-xl font-bold text-gray-900" id="totalTemplates">0</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-400">
                <span class="text-green-600" id="templateGrowth">+0%</span> vs last period
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Views</p>
                    <p class="text-xl font-bold text-blue-600" id="totalViews">0</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-400">
                <span class="text-blue-600" id="viewGrowth">+0%</span> vs last period
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Downloads</p>
                    <p class="text-xl font-bold text-green-600" id="totalDownloads">0</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="fas fa-download"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-400">
                <span class="text-green-600" id="downloadGrowth">+0%</span> vs last period
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Conversion Rate</p>
                    <p class="text-xl font-bold text-purple-600" id="conversionRate">0%</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-percent"></i>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-400">
                <span class="text-purple-600" id="conversionGrowth">+0%</span> vs last period
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Template Usage Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Template Usage Trends</h3>
                <div class="flex items-center space-x-2">
                    <button onclick="setChartType('usage', 'line')" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Line</button>
                    <button onclick="setChartType('usage', 'bar')" class="text-xs text-gray-500 hover:text-gray-700 font-medium">Bar</button>
                </div>
            </div>
            <div class="h-64">
                <canvas id="usageTrendsChart"></canvas>
            </div>
        </div>

        <!-- Top Templates Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-900">Top Templates</h3>
                <span class="text-xs text-gray-500">by views</span>
            </div>
            <div class="h-64">
                <canvas id="topTemplatesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Category Distribution -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Category Distribution</h3>
            <div class="h-48">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>

        <!-- Popular Features -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-900 mb-4">Popular Features</h3>
            <div class="space-y-3" id="popularFeatures">
                <div class="animate-pulse flex items-center space-x-3">
                    <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                    <div class="flex-1 h-2 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded w-12"></div>
                </div>
                <div class="animate-pulse flex items-center space-x-3">
                    <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                    <div class="flex-1 h-2 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded w-10"></div>
                </div>
                <div class="animate-pulse flex items-center space-x-3">
                    <div class="h-4 bg-gray-200 rounded w-1/5"></div>
                    <div class="flex-1 h-2 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded w-8"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-wrap items-center justify-between gap-2">
            <h3 class="text-sm font-medium text-gray-900">Template Performance</h3>
            <div class="flex items-center space-x-2">
                <select id="templateFilter" class="text-sm border-gray-300 rounded-lg focus:border-primary-500 focus:ring-primary-500">
                    <option value="all">All Templates</option>
                    <option value="active">Active Only</option>
                    <option value="inactive">Inactive Only</option>
                    <option value="featured">Featured</option>
                </select>
                <input type="text" id="templateSearch" placeholder="Search templates..." class="text-sm border-gray-300 rounded-lg focus:border-primary-500 focus:ring-primary-500 px-3 py-1">
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Downloads</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Used</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="templatesTableBody">
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">Loading templates...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex flex-wrap items-center justify-between gap-3">
            <div class="text-sm text-gray-500">
                Showing <span id="showingStart">0</span> to <span id="showingEnd">0</span> of <span id="totalResults">0</span> templates
            </div>
            <div class="flex items-center space-x-2" id="paginationControls">
                <button onclick="changePage('prev')" class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50" id="prevPage">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span id="currentPage" class="text-sm text-gray-700">1</span>
                <button onclick="changePage('next')" class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50 disabled:opacity-50" id="nextPage">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let usageChart, topTemplatesChart, categoryChart;
    let currentPage = 1;
    let currentPeriod = 'week';
    let chartTypes = {
        usage: 'line'
    };

    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        fetchTemplatesData();
        setupPeriodSelector();
        setupFilters();
    });

    // ============================================
    // FETCH DATA
    // ============================================
    async function fetchTemplatesData() {
        try {
            showLoading();
            
            const response = await fetch(`/admin/api/analytics/templates?period=${currentPeriod}&page=${currentPage}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Failed to fetch data');

            const data = await response.json();
            
            if (data.success) {
                updateStats(data.data.stats);
                updateCharts(data.data.charts);
                updateTable(data.data.templates);
                updatePagination(data.data.pagination);
                updatePopularFeatures(data.data.popular_features);
            } else {
                showError(data.message || 'Failed to load template data');
            }
        } catch (error) {
            console.error('Error fetching templates data:', error);
            showError('Unable to load template data. Please try again.');
        } finally {
            hideLoading();
        }
    }

    // ============================================
    // UPDATE STATS
    // ============================================
    function updateStats(stats) {
        document.getElementById('totalTemplates').textContent = stats.total || 0;
        document.getElementById('totalViews').textContent = stats.total_views || 0;
        document.getElementById('totalDownloads').textContent = stats.total_downloads || 0;
        document.getElementById('conversionRate').textContent = (stats.conversion_rate || 0) + '%';
        
        document.getElementById('templateGrowth').textContent = (stats.growth || 0) + '%';
        document.getElementById('viewGrowth').textContent = (stats.view_growth || 0) + '%';
        document.getElementById('downloadGrowth').textContent = (stats.download_growth || 0) + '%';
        document.getElementById('conversionGrowth').textContent = (stats.conversion_growth || 0) + '%';
    }

    // ============================================
    // UPDATE CHARTS
    // ============================================
    function updateCharts(charts) {
        // Usage Trends Chart
        if (usageChart) usageChart.destroy();
        usageChart = new Chart(document.getElementById('usageTrendsChart'), {
            type: chartTypes.usage,
            data: {
                labels: charts.usage.labels || [],
                datasets: [
                    {
                        label: 'Views',
                        data: charts.usage.views || [],
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Downloads',
                        data: charts.usage.downloads || [],
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Top Templates Chart
        if (topTemplatesChart) topTemplatesChart.destroy();
        topTemplatesChart = new Chart(document.getElementById('topTemplatesChart'), {
            type: 'bar',
            data: {
                labels: charts.top_templates.labels || [],
                datasets: [{
                    label: 'Views',
                    data: charts.top_templates.data || [],
                    backgroundColor: ['#6366f1', '#8b5cf6', '#3b82f6', '#22c55e', '#f59e0b'],
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Category Distribution Chart
        if (categoryChart) categoryChart.destroy();
        categoryChart = new Chart(document.getElementById('categoryDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: charts.category_distribution.labels || [],
                datasets: [{
                    data: charts.category_distribution.data || [],
                    backgroundColor: [
                        '#6366f1', '#8b5cf6', '#3b82f6', '#22c55e', 
                        '#f59e0b', '#ef4444', '#ec4899', '#14b8a6'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    // ============================================
    // UPDATE TABLE
    // ============================================
    function updateTable(templates) {
        const tbody = document.getElementById('templatesTableBody');
        
        if (!templates || templates.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-layer-group text-gray-300 text-3xl mb-3 block"></i>
                        No templates found
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        templates.forEach(template => {
            const statusColors = {
                'active': 'bg-green-100 text-green-800',
                'inactive': 'bg-gray-100 text-gray-800',
                'draft': 'bg-yellow-100 text-yellow-800',
                'archived': 'bg-red-100 text-red-800'
            };
            const color = statusColors[template.status] || 'bg-gray-100 text-gray-800';
            
            html += `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-primary-100 text-primary-600 flex items-center justify-center">
                                <i class="fas fa-file-code"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${template.name}</p>
                                <p class="text-xs text-gray-500">${template.id}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">${template.category || 'Uncategorized'}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${color}">
                            ${template.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${template.views}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${template.downloads}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${template.conversion_rate || 0}%</td>
                    <td class="px-6 py-4 text-sm text-gray-500">${template.last_used || 'Never'}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <button onclick="viewTemplate('${template.id}')" class="text-gray-400 hover:text-blue-600 transition-colors" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editTemplate('${template.id}')" class="text-gray-400 hover:text-primary-600 transition-colors" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button onclick="previewTemplate('${template.id}')" class="text-gray-400 hover:text-green-600 transition-colors" title="Preview">
                                <i class="fas fa-external-link-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    // ============================================
    // UPDATE POPULAR FEATURES
    // ============================================
    function updatePopularFeatures(features) {
        const container = document.getElementById('popularFeatures');
        
        if (!features || features.length === 0) {
            container.innerHTML = `
                <div class="text-center text-gray-500 text-sm py-4">No feature data available</div>
            `;
            return;
        }

        let html = '';
        const maxCount = Math.max(...features.map(f => f.count));
        
        features.forEach(feature => {
            const percentage = (feature.count / maxCount) * 100;
            const barColor = feature.count >= maxCount * 0.8 ? 'bg-primary-600' :
                            feature.count >= maxCount * 0.5 ? 'bg-blue-500' :
                            'bg-gray-400';
            
            html += `
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-700">${feature.name}</span>
                        <span class="text-gray-500">${feature.count}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="${barColor} h-2 rounded-full transition-all duration-500" style="width: ${percentage}%"></div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }

    // ============================================
    // PAGINATION
    // ============================================
    function updatePagination(pagination) {
        document.getElementById('showingStart').textContent = pagination.from || 0;
        document.getElementById('showingEnd').textContent = pagination.to || 0;
        document.getElementById('totalResults').textContent = pagination.total || 0;
        document.getElementById('currentPage').textContent = pagination.current_page || 1;
        
        document.getElementById('prevPage').disabled = !pagination.prev_page_url;
        document.getElementById('nextPage').disabled = !pagination.next_page_url;
    }

    function changePage(direction) {
        if (direction === 'prev') {
            currentPage--;
        } else {
            currentPage++;
        }
        fetchTemplatesData();
    }

    // ============================================
    // FILTERS
    // ============================================
    function setupFilters() {
        document.getElementById('templateFilter').addEventListener('change', function() {
            currentPage = 1;
            fetchTemplatesData();
        });
        
        let searchTimeout;
        document.getElementById('templateSearch').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                fetchTemplatesData();
            }, 500);
        });
    }

    // ============================================
    // PERIOD SELECTOR
    // ============================================
    function setupPeriodSelector() {
        document.getElementById('period').addEventListener('change', function() {
            currentPeriod = this.value;
            currentPage = 1;
            fetchTemplatesData();
        });
    }

    // ============================================
    // CHART TYPE TOGGLE
    // ============================================
    function setChartType(chart, type) {
        chartTypes[chart] = type;
        fetchTemplatesData();
    }

    // ============================================
    // ACTIONS
    // ============================================
    function viewTemplate(id) {
        window.location.href = `/admin/templates/${id}`;
    }

    function editTemplate(id) {
        window.location.href = `/admin/templates/${id}/edit`;
    }

    function previewTemplate(id) {
        window.open(`/templates/${id}/preview`, '_blank');
    }

    function refreshData() {
        fetchTemplatesData();
    }

    // ============================================
    // LOADING STATES
    // ============================================
    function showLoading() {
        document.getElementById('totalTemplates').innerHTML = '<div class="animate-pulse h-6 w-16 bg-gray-200 rounded"></div>';
        document.getElementById('totalViews').innerHTML = '<div class="animate-pulse h-6 w-16 bg-gray-200 rounded"></div>';
        document.getElementById('totalDownloads').innerHTML = '<div class="animate-pulse h-6 w-16 bg-gray-200 rounded"></div>';
        document.getElementById('conversionRate').innerHTML = '<div class="animate-pulse h-6 w-12 bg-gray-200 rounded"></div>';
    }

    function hideLoading() {
        // Stats will be updated by updateStats()
    }

    function showError(message) {
        const tbody = document.getElementById('templatesTableBody');
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center">
                    <i class="fas fa-exclamation-circle text-red-400 text-3xl mb-3 block"></i>
                    <p class="text-red-500 text-lg font-medium">${message}</p>
                    <button onclick="fetchTemplatesData()" class="mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        <i class="fas fa-redo mr-2"></i> Retry
                    </button>
                </td>
            </tr>
        `;
    }
</script>
@endpush

@push('styles')
<style>
    .card-hover {
        transition: all 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endpush
@endsection