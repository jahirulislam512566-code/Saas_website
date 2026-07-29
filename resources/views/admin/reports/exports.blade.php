<!-- admin/reports/exports.blade.php -->
@extends('layout.app')

@section('title', 'Export Reports')
@section('page-title', 'Export Reports')
@section('page-subtitle', 'Generate and download custom reports in various formats')

@section('content')
<div x-data="exportManager()" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Export Options -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Generate Export</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Select the data you want to export</p>
                </div>
                
                <div class="p-6">
                    <form @submit.prevent="generateExport()" class="space-y-6">
                        <!-- Export Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data to Export *</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                       :class="form.type === 'customers' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10' : ''">
                                    <input type="radio" x-model="form.type" value="customers" class="text-indigo-600">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Customers</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Customer data and segments</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                       :class="form.type === 'sales' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10' : ''">
                                    <input type="radio" x-model="form.type" value="sales" class="text-indigo-600">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Sales</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Sales and revenue data</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                       :class="form.type === 'invoices' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10' : ''">
                                    <input type="radio" x-model="form.type" value="invoices" class="text-indigo-600">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Invoices</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Invoice and payment data</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                       :class="form.type === 'subscriptions' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10' : ''">
                                    <input type="radio" x-model="form.type" value="subscriptions" class="text-indigo-600">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Subscriptions</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Subscription analytics</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                       :class="form.type === 'websites' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10' : ''">
                                    <input type="radio" x-model="form.type" value="websites" class="text-indigo-600">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Websites</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Website performance data</p>
                                    </div>
                                </label>
                                
                                <label class="flex items-center gap-2 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                       :class="form.type === 'taxes' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/10' : ''">
                                    <input type="radio" x-model="form.type" value="taxes" class="text-indigo-600">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Taxes</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Tax and compliance data</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date Range</label>
                            <div class="flex items-center gap-3">
                                <input type="date" x-model="form.date_from" 
                                       class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <span class="text-gray-500">to</span>
                                <input type="date" x-model="form.date_to" 
                                       class="flex-1 px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Format & Options -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Export Format</label>
                                <div class="flex gap-3">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" x-model="form.format" value="csv" checked>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">CSV</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" x-model="form.format" value="excel">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Excel</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" x-model="form.format" value="pdf">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">PDF</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Include</label>
                                <div class="flex flex-wrap gap-3">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" x-model="form.include_charts">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Charts</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" x-model="form.include_summary">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Summary</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Export -->
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <input type="checkbox" x-model="form.schedule" id="schedule_export">
                            <label for="schedule_export" class="text-sm text-gray-700 dark:text-gray-300">Schedule recurring export</label>
                            <select x-show="form.schedule" x-model="form.schedule_frequency" 
                                    class="px-3 py-1 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg text-sm">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <button type="submit" 
                                    class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all"
                                    x-text="generating ? 'Generating...' : 'Generate Export'"
                                    :disabled="generating">
                                <i class="fas fa-file-export mr-2"></i>
                                <span x-text="generating ? 'Generating...' : 'Generate Export'"></span>
                            </button>
                            <button type="reset" 
                                    class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Exports -->
        <div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Recent Exports</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Your recently generated reports</p>
                </div>
                
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="export in recentExports" :key="export.id">
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="export.name"></span>
                                        <span class="text-xs px-1.5 py-0.5 rounded"
                                              :class="{
                                                'bg-green-100 text-green-700': export.status === 'completed',
                                                'bg-yellow-100 text-yellow-700': export.status === 'processing',
                                                'bg-red-100 text-red-700': export.status === 'failed'
                                              }"
                                              x-text="export.status"></span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="export.created_at"></p>
                                    <div class="flex items-center gap-3 mt-2">
                                        <span class="text-xs text-gray-500 dark:text-gray-400" x-text="export.size"></span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400" x-text="export.format.toUpperCase()"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 ml-2">
                                    <a :href="export.url" target="_blank" 
                                       class="p-1.5 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button @click="deleteExport(export)" 
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="recentExports.length === 0" class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-file-export text-2xl text-gray-300 dark:text-gray-700 mb-2"></i>
                        <p class="text-sm">No exports yet</p>
                        <p class="text-xs mt-1">Generate your first export above</p>
                    </div>
                </div>
            </div>

            <!-- Quick Export Stats -->
            <div class="mt-4 grid grid-cols-2 gap-3">
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-3 text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="stats.total">0</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Exports</p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-3 text-center">
                    <p class="text-2xl font-bold text-green-600" x-text="stats.this_month">0</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">This Month</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Progress Modal -->
    <div x-show="showProgress" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        
        <div x-show="showProgress"
             x-transition:enter="transition ease-out duration-200 delay-100"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-md w-full p-6 text-center">
            
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/20 flex items-center justify-center mb-4">
                    <i class="fas fa-spinner fa-spin text-2xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generating Export</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Please wait while we prepare your report...</p>
                <div class="w-full mt-4 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" 
                         :style="'width: ' + progress + '%'"></div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2" x-text="progress + '%'"></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportManager() {
    return {
        form: {
            type: 'customers',
            date_from: '',
            date_to: '',
            format: 'csv',
            include_charts: false,
            include_summary: true,
            schedule: false,
            schedule_frequency: 'weekly'
        },
        recentExports: @json($recentExports),
        stats: @json($stats),
        generating: false,
        showProgress: false,
        progress: 0,
        
        generateExport() {
            this.generating = true;
            this.showProgress = true;
            this.progress = 0;
            
            // Simulate progress
            const interval = setInterval(() => {
                if (this.progress < 90) {
                    this.progress += Math.random() * 10;
                }
            }, 500);
            
            fetch('{{ route("admin.reports.export.generate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(interval);
                this.progress = 100;
                this.generating = false;
                
                setTimeout(() => {
                    this.showProgress = false;
                    if (data.success) {
                        // Add to recent exports
                        this.recentExports.unshift(data.export);
                        // Download file
                        window.open(data.url, '_blank');
                    }
                }, 500);
            })
            .catch(error => {
                clearInterval(interval);
                this.generating = false;
                this.showProgress = false;
                alert('Error generating export. Please try again.');
            });
        },
        
        deleteExport(exportItem) {
            if (confirm('Delete this export?')) {
                fetch(`{{ route('admin.reports.export.delete', '') }}/${exportItem.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.recentExports = this.recentExports.filter(e => e.id !== exportItem.id);
                        this.stats.total--;
                    }
                });
            }
        }
    }
}
</script>
@endpush