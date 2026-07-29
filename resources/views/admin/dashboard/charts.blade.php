{{-- resources/views/admin/dashboard/charts.blade.php --}}

<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Select Period:</div>
                        <a class="dropdown-item" href="#" data-period="7">Last 7 Days</a>
                        <a class="dropdown-item" href="#" data-period="30">Last 30 Days</a>
                        <a class="dropdown-item" href="#" data-period="90">Last 90 Days</a>
                        <a class="dropdown-item" href="#" data-period="365">Last Year</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="revenuePieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2">
                        <i class="fas fa-circle text-primary"></i> Services
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-success"></i> Products
                    </span>
                    <span class="mr-2">
                        <i class="fas fa-circle text-info"></i> Subscriptions
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================
        // REVENUE CHART
        // ============================================
        const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
        
        if (revenueCtx) {
            // Get data from PHP
            const revenueData = @json($revenueData ?? []);
            const revenueLabels = @json($revenueLabels ?? []);
            
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: revenueLabels.length > 0 ? revenueLabels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: revenueData.length > 0 ? revenueData : [0, 0, 0, 0, 0, 0],
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#4e73df',
                        pointHoverBackgroundColor: '#2e59d9',
                        pointHoverBorderColor: '#2e59d9',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.parsed.y.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            }
                        },
                        y: {
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toFixed(0);
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        }

        // ============================================
        // PIE CHART
        // ============================================
        const pieCtx = document.getElementById('revenuePieChart')?.getContext('2d');
        
        if (pieCtx) {
            const pieData = @json($pieData ?? []);
            const pieLabels = @json($pieLabels ?? []);
            
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: pieLabels.length > 0 ? pieLabels : ['Services', 'Products', 'Subscriptions'],
                    datasets: [{
                        data: pieData.length > 0 ? pieData : [300, 200, 100],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return '$' + context.parsed.toFixed(2) + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    cutout: '70%',
                }
            });
        }

        // ============================================
        // PERIOD SELECTOR
        // ============================================
        document.querySelectorAll('.dropdown-item[data-period]').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const period = this.dataset.period;
                
                // Update chart data based on selected period
                fetch('/admin/dashboard/chart-data?period=' + period)
                    .then(response => response.json())
                    .then(data => {
                        // Update chart with new data
                        if (window.revenueChart) {
                            window.revenueChart.data.labels = data.labels;
                            window.revenueChart.data.datasets[0].data = data.values;
                            window.revenueChart.update();
                        }
                    })
                    .catch(error => console.error('Error fetching chart data:', error));
            });
        });
    });
</script>
@endpush