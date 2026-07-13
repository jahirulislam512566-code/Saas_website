{{-- resources/views/admin/pages/components/modals.blade.php --}}
<!-- Add/Edit Component Modal -->
<div id="componentModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" onclick="document.getElementById('componentModal').style.display='none'">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <input type="hidden" name="section_id">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 modal-title">Add Component</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Component Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" required
                                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                           placeholder="e.g., Hero Image">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" required
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                        <option value="text">Text Block</option>
                                        <option value="image">Image Block</option>
                                        <option value="video">Video Block</option>
                                        <option value="button">Button</option>
                                        <option value="icon">Icon Block</option>
                                        <option value="card">Card</option>
                                        <option value="testimonial">Testimonial</option>
                                        <option value="counter">Counter</option>
                                        <option value="slider">Slider</option>
                                        <option value="form">Form</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Content (JSON)
                                    </label>
                                    <textarea name="content" rows="4"
                                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 font-mono text-sm"
                                              placeholder='{"text": "Hello World", "image": "url"}'></textarea>
                                </div>
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="is_active" value="1" checked
                                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Component
                    </button>
                    <button type="button" onclick="document.getElementById('componentModal').style.display='none'" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Component Form -->
<form id="component-delete-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>