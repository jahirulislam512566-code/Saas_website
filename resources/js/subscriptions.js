// resources/js/subscriptions.js or wherever you fetch subscriptions

async function fetchSubscriptions() {
    try {
        // Use the correct API endpoint
        const response = await fetch('/admin/api/subscriptions', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success) {
            renderSubscriptions(data.data);
            updateStats(data.total);
        } else {
            showError(data.message || 'Unable to fetch subscriptions. Please try again.');
        }
    } catch (error) {
        console.error('Error fetching subscriptions:', error);
        showError('Unable to fetch subscriptions. Please try again.');
    }
}