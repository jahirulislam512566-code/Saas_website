// resources/js/app.js
import './bootstrap';
import Alpine from 'alpinejs';
import { sidebarComponent } from './admin/sidebar';
import { dashboardComponent } from './admin/dashboard';

// Set window.Alpine BEFORE registering components
window.Alpine = Alpine;

// Register ALL Alpine.js components BEFORE starting Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebarComponent', sidebarComponent);
    Alpine.data('dashboardComponent', dashboardComponent);
    console.log('✅ All components registered');
});

// Start Alpine
Alpine.start();

console.log('✅ Alpine started');

// ============ SUBSCRIPTION FETCH FUNCTION ============
async function fetchSubscriptions() {
    try {
        const response = await fetch('/admin/api/subscriptions', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            renderSubscriptions(data.data);
            return data;
        } else {
            showError(data.message || 'Unable to fetch subscriptions. Please try again.');
        }
    } catch (error) {
        console.error('Error fetching subscriptions:', error);
        showError('Unable to fetch subscriptions. Please try again.');
    }
}

function renderSubscriptions(subscriptions) {
    const container = document.getElementById('subscriptions-container');
    if (!container) {
        console.warn('Subscriptions container not found');
        return;
    }

    if (!subscriptions || subscriptions.length === 0) {
        container.innerHTML = `
            <div class="text-center py-12">
                <i class="fas fa-receipt text-4xl text-gray-300 mb-3 block"></i>
                <p class="text-lg font-medium text-gray-900">No subscriptions found</p>
                <p class="text-sm text-gray-500 mt-1">Get started by creating your first subscription.</p>
            </div>
        `;
        return;
    }

    let html = '<div class="overflow-x-auto">';
    html += '<table class="min-w-full divide-y divide-gray-200">';
    html += `
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Billing</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
    `;

    subscriptions.forEach(sub => {
        const statusColors = {
            'active': 'bg-green-100 text-green-800',
            'trialing': 'bg-blue-100 text-blue-800',
            'past_due': 'bg-orange-100 text-orange-800',
            'canceled': 'bg-red-100 text-red-800',
            'unpaid': 'bg-red-100 text-red-800',
            'incomplete': 'bg-gray-100 text-gray-800',
            'paused': 'bg-yellow-100 text-yellow-800',
        };
        const color = statusColors[sub.status] || 'bg-gray-100 text-gray-800';

        html += `
            <tr>
                <td class="px-6 py-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900">${sub.user?.name || 'N/A'}</p>
                        <p class="text-xs text-gray-500">${sub.user?.email || 'N/A'}</p>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm font-medium text-gray-900">${sub.plan?.name || 'N/A'}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${color}">
                        ${sub.status.charAt(0).toUpperCase() + sub.status.slice(1).replace('_', ' ')}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm font-medium text-gray-900">${sub.currency || '$'}${(sub.amount || 0).toFixed(2)}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-500">${sub.billing_cycle || 'monthly'}</span>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-500">${sub.created_at ? new Date(sub.created_at).toLocaleDateString() : 'N/A'}</p>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="/admin/subscriptions/${sub.id}" class="text-gray-400 hover:text-blue-600" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/admin/subscriptions/${sub.id}/edit" class="text-gray-400 hover:text-primary-600" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        ${sub.status !== 'canceled' ? `
                            <button onclick="cancelSubscription(${sub.id})" class="text-gray-400 hover:text-red-600" title="Cancel">
                                <i class="fas fa-ban"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    });

    html += '</tbody></table></div>';
    container.innerHTML = html;
}

function showError(message) {
    const errorDiv = document.getElementById('subscription-error');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    } else {
        const container = document.getElementById('subscriptions-container');
        if (container) {
            container.innerHTML = `
                <div class="text-center py-12 text-red-600">
                    <i class="fas fa-exclamation-circle text-4xl mb-3 block"></i>
                    <p class="text-lg font-medium">${message}</p>
                    <button onclick="fetchSubscriptions()" class="mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        <i class="fas fa-redo mr-2"></i> Retry
                    </button>
                </div>
            `;
        }
    }
}

// Make functions globally accessible
window.fetchSubscriptions = fetchSubscriptions;
window.renderSubscriptions = renderSubscriptions;
window.showError = showError;

// Auto-fetch on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Admin panel initialized');
    
    if (document.getElementById('subscriptions-container')) {
        fetchSubscriptions();
    }
});

// Auto-refresh every 60 seconds if on subscriptions page
setInterval(() => {
    if (document.getElementById('subscriptions-container')) {
        fetchSubscriptions();
    }
}, 60000);