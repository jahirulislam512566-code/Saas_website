// resources/js/admin/dashboard.js
export function dashboardComponent() {
    return {
        loading: false,
        stats: {},
        
        initDashboard() {
            console.log('✅ Dashboard component initialized');
            this.loadStats();
        },
        
        async loadStats() {
            this.loading = true;
            try {
                const response = await fetch('/admin/api/dashboard/stats');
                const data = await response.json();
                if (data.success) {
                    this.stats = data.data;
                    this.updateStats();
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            } finally {
                this.loading = false;
            }
        },
        
        updateStats() {
            // Update stats in the UI
            const statsGrid = document.getElementById('statsGrid');
            if (statsGrid && this.stats) {
                // Update stats dynamically
            }
        }
    };
}