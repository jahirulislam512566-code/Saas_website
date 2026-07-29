{{-- resources/views/admin/customers/components/charts.blade.php --}}

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Customer Growth</h5>
            </div>
            <div class="card-body">
                <canvas id="customerGrowthChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Customer Status Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="customerStatusChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Customer Growth Chart
        const growthCtx = document.getElementById('customerGrowthChart')?.getContext('2d');
        if (growthCtx) {
            new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chart_data['months'] ?? []) !!},
                    datasets: [{
                        label: 'New Customers',
                        data: {!! json_encode($chart_data['new_customers'] ?? []) !!},
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        tension: 0.3,
                        fill: true,
                    }, {
                        label: 'Total Customers',
                        data: {!! json_encode($chart_data['total_customers'] ?? []) !!},
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.05)',
                        tension: 0.3,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                            }
                        }
                    }
                }
            });
        }

        // Customer Status Chart
        const statusCtx = document.getElementById('customerStatusChart')?.getContext('2d');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chart_data['status_labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($chart_data['status_counts'] ?? []) !!},
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#f6c23e', '#e74a3b',
                            '#36b9cc', '#858796', '#6f42c1', '#fd7e14'
                        ],
                        hoverBackgroundColor: [
                            '#2e59d9', '#17a673', '#dda20a', '#be2617',
                            '#2c9faf', '#6b6b6b', '#5a32a3', '#d3650e'
                        ],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    },
                    cutout: '70%',
                },
            });
        }
    });
</script>
@endpush