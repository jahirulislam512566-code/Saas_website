<!-- admin/dashboard/index.blade.php -->


<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Welcome back, <?php echo e(auth()->user()->name); ?>! Here\'s what\'s happening with your business.'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="dashboard()" x-init="init()">
    <!-- Stats Cards -->
    <?php echo $__env->make('admin.dashboard.statistics', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Charts Row -->
    <?php echo $__env->make('admin.dashboard.charts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Revenue & Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Revenue Widget -->
        <div class="lg:col-span-2">
            <?php echo $__env->make('admin.dashboard.revenue', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <!-- Activities Widget -->
        <div>
            <?php echo $__env->make('admin.dashboard.activities', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <!-- Quick Actions & Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <?php echo $__env->make('admin.dashboard.widgets', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function dashboard() {
    return {
        stats: <?php echo json_encode($stats ?? [], 15, 512) ?>,
        recentActivities: <?php echo json_encode($recentActivities ?? [], 15, 512) ?>,
        latestPayments: <?php echo json_encode($latestPayments ?? [], 15, 512) ?>,
        serverStatus: <?php echo json_encode($serverStatus ?? [], 15, 512) ?>,
        
        init() {
            this.startAutoRefresh();
        },
        
        startAutoRefresh() {
            setInterval(() => {
                this.refreshData();
            }, 60000); // Refresh every minute
        },
        
        refreshData() {
            fetch('<?php echo e(route("admin.dashboard.refresh")); ?>')
                .then(response => response.json())
                .then(data => {
                    this.stats = data.stats;
                    this.recentActivities = data.activities;
                    this.latestPayments = data.payments;
                    this.serverStatus = data.server;
                    
                    // Update charts
                    window.dispatchEvent(new CustomEvent('dashboard-data-updated', { 
                        detail: data 
                    }));
                })
                .catch(error => console.error('Error refreshing dashboard:', error));
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Saas website\webcraft\resources\views/admin/dashboard/index.blade.php ENDPATH**/ ?>