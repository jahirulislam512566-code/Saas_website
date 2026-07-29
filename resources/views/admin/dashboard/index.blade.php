<!-- admin/dashboard/index.blade.php -->
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, {{ auth()->user()->name }}! Here\'s what\'s happening with your business.')

@section('content')
<div x-data="dashboard()" x-init="init()">
    <!-- Stats Cards -->
    @include('admin.dashboard.statistics')

    <!-- Charts Row -->
    @include('admin.dashboard.charts')

    <!-- Revenue & Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Revenue Widget -->
        <div class="lg:col-span-2">
            @include('admin.dashboard.revenue')
        </div>
        <!-- Activities Widget -->
        <div>
            @include('admin.dashboard.activities')
        </div>
    </div>

    <!-- Quick Actions & Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        @include('admin.dashboard.widgets')
    </div>
</div>

@push('scripts')
<script>
function dashboard() {
    return {
        stats: @json($stats ?? []),
        recentActivities: @json($recentActivities ?? []),
        latestPayments: @json($latestPayments ?? []),
        serverStatus: @json($serverStatus ?? []),
        
        init() {
            this.startAutoRefresh();
        },
        
        startAutoRefresh() {
            setInterval(() => {
                this.refreshData();
            }, 60000); // Refresh every minute
        },
        
        refreshData() {
            fetch('{{ route("admin.dashboard.refresh") }}')
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
@endpush