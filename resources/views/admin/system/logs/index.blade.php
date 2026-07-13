{{-- resources/views/admin/logs/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Log Management')

@section('breadcrumb')
    <li>
        <div class="flex items-center">
            <i class="fas fa-chevron-right text-gray-400 mx-2 text-sm"></i>
            <span class="text-gray-500">Logs</span>
        </div>
    </li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- ===== HEADER ===== -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Log Management</h1>
            <p class="text-sm text-gray-500 mt-1">View and manage system logs</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2 bg-white rounded-lg shadow-sm px-3 py-2 border border-gray-200">
                <i class="fas fa-sync text-gray-400 text-sm"></i>
                <span class="text-xs text-gray-600" id="autoRefreshStatus">Auto-refresh: <span id="refreshTimer">30s</span></span>
                <button onclick="toggleAutoRefresh()" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                    <span id="refreshToggle">Pause</span>
                </button>
            </div>
            <button onclick="clearAllLogs()" 
                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i> Clear All
            </button>
            <button onclick="refreshLogs()" 
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-sync mr-2"></i> Refresh
            </button>
        </div>
    </div>

    <!-- ===== STATS CARDS ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Logs</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Today</p>
                    <p class="text-xl font-bold text-blue-600">{{ $stats['today'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Errors</p>
                    <p class="text-xl font-bold text-red-600">{{ $stats['errors'] ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-4 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Size</p>
                    <p class="text-xl font-bold text-purple-600">{{ $stats['total_size'] ?? '0 MB' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i class="fas fa-hdd"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== LOG FILE SELECTOR ===== -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Log File</label>
                <select id="log-file-selector" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        onchange="loadLogFile(this.value)">
                    @foreach($logFiles as $file)
                        <option value="{{ $file['name'] }}" {{ $currentFile == $file['name'] ? 'selected' : '' }}>
                            {{ $file['name'] }} ({{ $file['size'] }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button onclick="downloadLog()" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Download
                </button>
                <button onclick="deleteLogFile()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- ===== LOG CONTENT ===== -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <h3 class="text-sm font-medium text-gray-900">
                    <i class="fas fa-file-alt mr-2"></i>
                    {{ $currentFile }}
                </h3>
                <span class="text-xs text-gray-500">{{ $logSize ?? '0 KB' }}</span>
                <span class="text-xs text-gray-400">|</span>
                <span class="text-xs text-gray-500">{{ $logLines ?? 0 }} lines</span>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="scrollToTop()" class="text-gray-400 hover:text-gray-600 transition-colors" title="Scroll to top">
                    <i class="fas fa-arrow-up text-sm"></i>
                </button>
                <button onclick="scrollToBottom()" class="text-gray-400 hover:text-gray-600 transition-colors" title="Scroll to bottom">
                    <i class="fas fa-arrow-down text-sm"></i>
                </button>
                <div class="flex rounded-lg border border-gray-200 overflow-hidden">
                    <button onclick="changeLogLevel('all')" 
                            class="px-3 py-1 text-xs transition-colors {{ $logLevel == 'all' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                        All
                    </button>
                    <button onclick="changeLogLevel('error')" 
                            class="px-3 py-1 text-xs transition-colors {{ $logLevel == 'error' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                        Errors
                    </button>
                    <button onclick="changeLogLevel('warning')" 
                            class="px-3 py-1 text-xs transition-colors {{ $logLevel == 'warning' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                        Warnings
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Log Content -->
        <div id="log-content-container" class="bg-gray-900 p-4 overflow-x-auto max-h-[600px] overflow-y-auto font-mono text-sm">
            @forelse($logContent as $line)
                @php
                    $lineClass = '';
                    $lineIcon = 'fa-circle';
                    $lineColor = 'text-gray-400';
                    
                    if (strpos($line, 'ERROR') !== false || strpos($line, 'error') !== false) {
                        $lineClass = 'text-red-400';
                        $lineIcon = 'fa-exclamation-circle';
                        $lineColor = 'text-red-400';
                    } elseif (strpos($line, 'WARNING') !== false || strpos($line, 'warning') !== false) {
                        $lineClass = 'text-yellow-400';
                        $lineIcon = 'fa-exclamation-triangle';
                        $lineColor = 'text-yellow-400';
                    } elseif (strpos($line, 'INFO') !== false || strpos($line, 'info') !== false) {
                        $lineClass = 'text-blue-400';
                        $lineIcon = 'fa-info-circle';
                        $lineColor = 'text-blue-400';
                    } elseif (strpos($line, 'DEBUG') !== false || strpos($line, 'debug') !== false) {
                        $lineClass = 'text-gray-400';
                        $lineIcon = 'fa-bug';
                        $lineColor = 'text-gray-400';
                    } elseif (strpos($line, 'EMERGENCY') !== false || strpos($line, 'emergency') !== false) {
                        $lineClass = 'text-red-600';
                        $lineIcon = 'fa-skull';
                        $lineColor = 'text-red-600';
                    } elseif (strpos($line, 'ALERT') !== false || strpos($line, 'alert') !== false) {
                        $lineClass = 'text-orange-400';
                        $lineIcon = 'fa-bell';
                        $lineColor = 'text-orange-400';
                    } elseif (strpos($line, 'CRITICAL') !== false || strpos($line, 'critical') !== false) {
                        $lineClass = 'text-red-500';
                        $lineIcon = 'fa-heartbeat';
                        $lineColor = 'text-red-500';
                    } elseif (strpos($line, 'NOTICE') !== false || strpos($line, 'notice') !== false) {
                        $lineClass = 'text-teal-400';
                        $lineIcon = 'fa-info';
                        $lineColor = 'text-teal-400';
                    }
                @endphp
                <div class="flex items-start space-x-3 py-0.5 hover:bg-gray-800 rounded px-2 group">
                    <span class="text-gray-600 select-none w-8 text-right text-xs">{{ $loop->iteration }}</span>
                    <span class="flex-shrink-0 mt-0.5">
                        <i class="fas {{ $lineIcon }} {{ $lineColor }} text-xs"></i>
                    </span>
                    <span class="whitespace-pre-wrap break-all {{ $lineClass }}">{{ $line }}</span>
                    <button onclick="copyLine('{{ addslashes($line) }}')" 
                            class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-gray-300 transition-opacity flex-shrink-0 ml-2">
                        <i class="fas fa-copy text-xs"></i>
                    </button>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl text-gray-600 mb-3 block"></i>
                    <p class="text-lg font-medium">No log content</p>
                    <p class="text-sm mt-1">The log file is empty</p>
                </div>
            @endforelse
        </div>
        
        <!-- Log Footer -->
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <div class="text-xs text-gray-500">
                <span class="font-medium">{{ $logLines ?? 0 }}</span> lines shown
                @if($logLines > 0)
                    <span class="mx-2">|</span>
                    <span class="text-gray-400">Last updated: {{ now()->format('Y-m-d H:i:s') }}</span>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="copyAllLogs()" 
                        class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-copy mr-1"></i> Copy All
                </button>
                <span class="text-gray-300">|</span>
                <button onclick="downloadLog()" 
                        class="text-xs text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-download mr-1"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ===== FORMS ===== -->
<form id="clear-logs-form" method="POST" action="{{ route('admin.logs.clear') }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="delete-log-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
    let autoRefreshInterval = null;
    let refreshCountdown = 30;
    let isAutoRefreshEnabled = true;
    let currentLogLevel = 'all';

    // ============================================
    // AUTO-REFRESH
    // ============================================
    function toggleAutoRefresh() {
        isAutoRefreshEnabled = !isAutoRefreshEnabled;
        document.getElementById('refreshToggle').textContent = isAutoRefreshEnabled ? 'Pause' : 'Resume';
        
        if (isAutoRefreshEnabled) {
            startAutoRefresh();
        } else {
            clearInterval(autoRefreshInterval);
        }
    }

    function startAutoRefresh() {
        clearInterval(autoRefreshInterval);
        refreshCountdown = 30;
        updateTimerDisplay();
        
        autoRefreshInterval = setInterval(() => {
            refreshCountdown--;
            updateTimerDisplay();
            
            if (refreshCountdown <= 0) {
                refreshLogs();
                refreshCountdown = 30;
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        document.getElementById('refreshTimer').textContent = refreshCountdown + 's';
    }

    function refreshLogs() {
        const currentFile = document.getElementById('log-file-selector').value;
        const url = `/admin/logs/${currentFile}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateLogContent(data.data.content);
                updateStats(data.data.stats);
                document.getElementById('logLines').textContent = data.data.lines;
            }
        })
        .catch(error => console.error('Refresh failed:', error));
    }

    function updateLogContent(content) {
        const container = document.getElementById('log-content-container');
        if (!container) return;

        if (!content || content.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl text-gray-600 mb-3 block"></i>
                    <p class="text-lg font-medium">No log content</p>
                </div>
            `;
            return;
        }

        let html = '';
        content.forEach((line, index) => {
            let lineClass = '';
            let lineIcon = 'fa-circle';
            let lineColor = 'text-gray-400';
            
            if (line.includes('ERROR') || line.includes('error')) {
                lineClass = 'text-red-400';
                lineIcon = 'fa-exclamation-circle';
                lineColor = 'text-red-400';
            } else if (line.includes('WARNING') || line.includes('warning')) {
                lineClass = 'text-yellow-400';
                lineIcon = 'fa-exclamation-triangle';
                lineColor = 'text-yellow-400';
            } else if (line.includes('INFO') || line.includes('info')) {
                lineClass = 'text-blue-400';
                lineIcon = 'fa-info-circle';
                lineColor = 'text-blue-400';
            } else if (line.includes('DEBUG') || line.includes('debug')) {
                lineClass = 'text-gray-400';
                lineIcon = 'fa-bug';
                lineColor = 'text-gray-400';
            }

            html += `
                <div class="flex items-start space-x-3 py-0.5 hover:bg-gray-800 rounded px-2 group">
                    <span class="text-gray-600 select-none w-8 text-right text-xs">${index + 1}</span>
                    <span class="flex-shrink-0 mt-0.5">
                        <i class="fas ${lineIcon} ${lineColor} text-xs"></i>
                    </span>
                    <span class="whitespace-pre-wrap break-all ${lineClass}">${escapeHtml(line)}</span>
                    <button onclick="copyLine('${escapeHtml(line)}')" 
                            class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-gray-300 transition-opacity flex-shrink-0 ml-2">
                        <i class="fas fa-copy text-xs"></i>
                    </button>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    function updateStats(stats) {
        document.getElementById('totalLogs').textContent = stats.total || 0;
        document.getElementById('todayLogs').textContent = stats.today || 0;
        document.getElementById('errorLogs').textContent = stats.errors || 0;
        document.getElementById('totalSize').textContent = stats.total_size || '0 MB';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // ============================================
    // LOG FILE OPERATIONS
    // ============================================
    function loadLogFile(filename) {
        window.location.href = `/admin/logs/${filename}`;
    }

    function downloadLog() {
        const currentFile = document.getElementById('log-file-selector').value;
        window.location.href = `/admin/logs/${currentFile}/download`;
    }

    function deleteLogFile() {
        const currentFile = document.getElementById('log-file-selector').value;
        if (confirm(`Delete log file "${currentFile}"?`)) {
            const form = document.getElementById('delete-log-form');
            form.action = `/admin/logs/${currentFile}`;
            form.submit();
        }
    }

    function clearAllLogs() {
        if (confirm('Clear all log files?')) {
            document.getElementById('clear-logs-form').submit();
        }
    }

    // ============================================
    // LOG LEVEL FILTER
    // ============================================
    function changeLogLevel(level) {
        currentLogLevel = level;
        const container = document.getElementById('log-content-container');
        const lines = container.querySelectorAll('.flex.items-start');

        lines.forEach(line => {
            const text = line.textContent;
            if (level === 'all') {
                line.style.display = 'flex';
            } else if (level === 'error') {
                line.style.display = text.includes('ERROR') || text.includes('error') ? 'flex' : 'none';
            } else if (level === 'warning') {
                line.style.display = text.includes('WARNING') || text.includes('warning') ? 'flex' : 'none';
            }
        });
    }

    // ============================================
    // SCROLL FUNCTIONS
    // ============================================
    function scrollToTop() {
        const container = document.getElementById('log-content-container');
        container.scrollTop = 0;
    }

    function scrollToBottom() {
        const container = document.getElementById('log-content-container');
        container.scrollTop = container.scrollHeight;
    }

    // ============================================
    // COPY FUNCTIONS
    // ============================================
    function copyLine(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Line copied to clipboard!', 'success');
        });
    }

    function copyAllLogs() {
        const container = document.getElementById('log-content-container');
        const text = container.textContent;
        navigator.clipboard.writeText(text).then(() => {
            showToast('All logs copied to clipboard!', 'success');
        });
    }

    // ============================================
    // TOAST NOTIFICATIONS
    // ============================================
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } shadow-lg z-50`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    document.addEventListener('keydown', (e) => {
        // Ctrl+R to refresh
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            refreshLogs();
        }
        // Ctrl+F to search (open browser search)
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            // Let browser handle search
        }
        // ESC to close search
        if (e.key === 'Escape') {
            // Clear search if active
        }
    });

    // ============================================
    // INITIALIZATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        startAutoRefresh();
        
        // Auto-scroll to bottom on load
        setTimeout(scrollToBottom, 500);
    });
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
    
    #log-content-container {
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        font-size: 12px;
        line-height: 1.6;
        background: #0a0e17;
        color: #d4d4d4;
    }
    
    #log-content-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    #log-content-container::-webkit-scrollbar-track {
        background: #1a1a2e;
    }
    
    #log-content-container::-webkit-scrollbar-thumb {
        background: #4a4a6a;
        border-radius: 4px;
    }
    
    #log-content-container::-webkit-scrollbar-thumb:hover {
        background: #5a5a7a;
    }
    
    .line-number {
        color: #4a4a6a;
        user-select: none;
        width: 40px;
        text-align: right;
        padding-right: 12px;
        flex-shrink: 0;
    }
</style>
@endpush
@endsection