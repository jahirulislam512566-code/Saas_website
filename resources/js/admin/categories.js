// resources/js/admin/categories.js

async function fetchCategories() {
    try {
        const response = await fetch('/admin/api/categories', {
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
            renderCategories(data.data);
            return data;
        } else {
            showError(data.message || 'Unable to fetch categories. Please try again.');
        }
    } catch (error) {
        console.error('Error fetching categories:', error);
        showError('Unable to fetch categories. Please try again.');
    }
}

function renderCategories(categories) {
    const container = document.getElementById('categoriesContainer');
    if (!container) {
        console.warn('Categories container not found');
        return;
    }

    if (!categories || categories.length === 0) {
        container.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center">
                    <i class="fas fa-tags text-gray-300 text-4xl mb-3 block"></i>
                    <p class="text-gray-500 text-lg font-medium">No categories found</p>
                    <p class="text-gray-400 text-sm mt-1">Create your first category to get started</p>
                </td>
            </tr>
        `;
        return;
    }

    let html = '';
    categories.forEach(category => {
        const statusColor = category.is_active ? 'green' : 'gray';
        const statusText = category.is_active ? 'Active' : 'Inactive';
        
        html += `
            <tr class="hover:bg-gray-50 transition-colors group" data-id="${category.id}">
                <td class="px-4 py-3 text-sm text-gray-500">${category.id}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: ${category.color_hex}20;">
                            <i class="fas ${category.icon || 'fa-folder'}" style="color: ${category.color_hex};"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${category.name}</p>
                            <p class="text-xs text-gray-500">${category.slug}</p>
                        </div>
                        ${category.is_featured ? `
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star text-xs mr-0.5"></i>
                            </span>
                        ` : ''}
                    </div>
                </td>
                <td class="px-4 py-3">
                    ${category.parent_name ? `<span class="text-sm text-gray-600">${category.parent_name}</span>` : '<span class="text-sm text-gray-400">—</span>'}
                </td>
                <td class="px-4 py-3">
                    <span class="text-sm text-gray-600">${category.post_count || 0}</span>
                </td>
                <td class="px-4 py-3">
                    <button onclick="toggleStatus('${category.id}')" 
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium transition-all
                            ${category.is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200'}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 ${category.is_active ? 'bg-green-500' : 'bg-gray-400'}"></span>
                        ${statusText}
                    </button>
                </td>
                <td class="px-4 py-3">
                    <span class="text-sm text-gray-500">${category.sort_order || 0}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="text-sm text-gray-500">
                        <div>${category.formatted_created_at || new Date(category.created_at).toLocaleDateString()}</div>
                    </div>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end space-x-1">
                        <a href="/admin/categories/${category.id}" 
                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                           title="View">
                            <i class="fas fa-eye text-sm"></i>
                        </a>
                        <a href="/admin/categories/${category.id}/edit" 
                           class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" 
                           title="Edit">
                            <i class="fas fa-pen text-sm"></i>
                        </a>
                        <button onclick="deleteCategory('${category.id}', '${category.name}')" 
                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                title="Delete">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    container.innerHTML = html;
}

function showError(message) {
    const container = document.getElementById('categoriesContainer');
    if (container) {
        container.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center">
                    <i class="fas fa-exclamation-circle text-red-400 text-4xl mb-3 block"></i>
                    <p class="text-red-500 text-lg font-medium">${message}</p>
                    <button onclick="fetchCategories()" class="mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        <i class="fas fa-redo mr-2"></i> Retry
                    </button>
                </td>
            </tr>
        `;
    }
}

// Auto-fetch on page load
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('categoriesContainer')) {
        fetchCategories();
    }
});