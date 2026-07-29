<!-- admin/support/conversations.blade.php -->
@extends('layout.app')

@section('title', 'Live Chat')
@section('page-title', 'Live Chat')
@section('page-subtitle', 'Real-time customer support conversations')

@section('content')
<div x-data="chatManager()" x-init="init()">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Chat List -->
        <div class="lg:col-span-1 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-gray-900 dark:text-white">Conversations</h4>
                    <span class="px-2 py-0.5 text-xs bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                        <span x-text="activeConversations"></span> active
                    </span>
                </div>
                <div class="mt-2 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" x-model="searchQuery" 
                           placeholder="Search conversations..." 
                           class="w-full pl-9 pr-3 py-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="overflow-y-auto h-[600px] divide-y divide-gray-100 dark:divide-gray-800">
                <template x-for="conversation in filteredConversations" :key="conversation.id">
                    <div @click="selectConversation(conversation)" 
                         class="p-3 hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer transition-colors"
                         :class="{'bg-indigo-50 dark:bg-indigo-900/10': selectedConversation?.id === conversation.id}">
                        <div class="flex items-start gap-3">
                            <div class="relative flex-shrink-0">
                                <img :src="conversation.user?.avatar || 'https://ui-avatars.com/api/?name=' + (conversation.user?.name || 'Guest')" 
                                     alt="" class="w-10 h-10 rounded-full">
                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full border-2 border-white dark:border-gray-900"
                                      :class="conversation.is_online ? 'bg-green-500' : 'bg-gray-400'"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-gray-900 dark:text-white truncate" x-text="conversation.user?.name || 'Guest'"></p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="formatTime(conversation.last_message_at)"></span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 truncate" x-text="conversation.last_message || 'No messages'"></p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs px-1.5 py-0.5 rounded"
                                          :class="conversation.status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400'"
                                          x-text="conversation.status"></span>
                                    <span x-show="conversation.unread_count > 0" 
                                          class="ml-auto bg-indigo-600 text-white text-xs px-2 py-0.5 rounded-full"
                                          x-text="conversation.unread_count"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="filteredConversations.length === 0" class="p-8 text-center text-gray-500 dark:text-gray-400">
                    <i class="fas fa-comments text-2xl text-gray-300 dark:text-gray-700 mb-2"></i>
                    <p>No conversations found</p>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="lg:col-span-3 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden flex flex-col">
            <template x-if="selectedConversation">
                <div class="flex flex-col h-full">
                    <!-- Chat Header -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img :src="selectedConversation.user?.avatar || 'https://ui-avatars.com/api/?name=' + (selectedConversation.user?.name || 'Guest')" 
                                 alt="" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white" x-text="selectedConversation.user?.name || 'Guest'"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full" 
                                              :class="selectedConversation.is_online ? 'bg-green-500' : 'bg-gray-400'"></span>
                                        <span x-text="selectedConversation.is_online ? 'Online' : 'Offline'"></span>
                                    </span>
                                    <span x-show="selectedConversation.typing" class="ml-2 text-indigo-600 dark:text-indigo-400 animate-pulse">
                                        Typing...
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button @click="transferChat()" 
                                    class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-user-plus"></i>
                            </button>
                            <button @click="endChat()" 
                                    class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <i class="fas fa-phone-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3" x-ref="messagesContainer">
                        <template x-for="message in selectedConversation.messages" :key="message.id">
                            <div class="flex" :class="message.is_agent ? 'justify-end' : 'justify-start'">
                                <div class="max-w-[70%]">
                                    <div class="rounded-lg p-3"
                                         :class="message.is_agent ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white'">
                                        <p class="text-sm" x-text="message.content"></p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="formatTime(message.created_at)"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Message Input -->
                    <div class="p-4 border-t border-gray-200 dark:border-gray-800">
                        <form @submit.prevent="sendMessage()" class="flex items-center gap-3">
                            <div class="flex-1 relative">
                                <input type="text" x-model="newMessage" 
                                       placeholder="Type a message..." 
                                       class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <button type="button" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <button type="button" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fas fa-smile"></i>
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </template>
            
            <template x-if="!selectedConversation">
                <div class="flex-1 flex items-center justify-center text-gray-500 dark:text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-comment-dots text-4xl text-gray-300 dark:text-gray-700 mb-3"></i>
                        <p>Select a conversation to start chatting</p>
                        <p class="text-sm mt-1">Or wait for a new customer to start a chat</p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function chatManager() {
    return {
        conversations: @json($conversations),
        selectedConversation: null,
        searchQuery: '',
        newMessage: '',
        activeConversations: 0,
        
        get filteredConversations() {
            if (!this.searchQuery) return this.conversations;
            const query = this.searchQuery.toLowerCase();
            return this.conversations.filter(c => 
                c.user?.name?.toLowerCase().includes(query) ||
                c.last_message?.toLowerCase().includes(query)
            );
        },
        
        init() {
            this.activeConversations = this.conversations.filter(c => c.status === 'active').length;
            // Initialize Echo for real-time updates
            this.initEcho();
        },
        
        initEcho() {
            // Pusher/Echo setup for real-time chat
            Echo.channel('chat')
                .listen('NewMessage', (e) => {
                    this.handleNewMessage(e);
                })
                .listen('UserTyping', (e) => {
                    this.handleTyping(e);
                })
                .listen('UserOnline', (e) => {
                    this.handleUserOnline(e);
                });
        },
        
        selectConversation(conversation) {
            this.selectedConversation = conversation;
            // Mark messages as read
            this.markAsRead(conversation.id);
            this.scrollToBottom();
        },
        
        sendMessage() {
            if (!this.newMessage.trim() || !this.selectedConversation) return;
            
            const message = {
                id: Date.now(),
                content: this.newMessage,
                is_agent: true,
                created_at: new Date().toISOString()
            };
            
            // Optimistic update
            this.selectedConversation.messages.push(message);
            this.scrollToBottom();
            
            // Send to server
            fetch(`{{ route('admin.support.chat.send') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    conversation_id: this.selectedConversation.id,
                    message: this.newMessage
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    // Rollback on error
                    this.selectedConversation.messages.pop();
                }
            })
            .catch(() => {
                this.selectedConversation.messages.pop();
            });
            
            this.newMessage = '';
        },
        
        scrollToBottom() {
            setTimeout(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            }, 100);
        },
        
        markAsRead(conversationId) {
            fetch(`{{ route('admin.support.chat.mark-read') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ conversation_id: conversationId })
            });
        },
        
        handleNewMessage(e) {
            const conversation = this.conversations.find(c => c.id === e.conversation_id);
            if (conversation) {
                conversation.messages.push(e.message);
                conversation.last_message = e.message.content;
                conversation.last_message_at = e.message.created_at;
                
                if (!conversation.is_online) {
                    conversation.unread_count = (conversation.unread_count || 0) + 1;
                }
                
                if (this.selectedConversation?.id === conversation.id) {
                    this.scrollToBottom();
                }
            }
        },
        
        handleTyping(e) {
            const conversation = this.conversations.find(c => c.id === e.conversation_id);
            if (conversation) {
                conversation.typing = e.is_typing;
            }
        },
        
        handleUserOnline(e) {
            const conversation = this.conversations.find(c => c.id === e.conversation_id);
            if (conversation) {
                conversation.is_online = e.is_online;
            }
        },
        
        transferChat() {
            if (!this.selectedConversation) return;
            // Implementation for transferring chat to another agent
        },
        
        endChat() {
            if (!this.selectedConversation) return;
            if (confirm('Are you sure you want to end this chat?')) {
                fetch(`{{ route('admin.support.chat.end') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ conversation_id: this.selectedConversation.id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.selectedConversation.status = 'ended';
                        this.selectedConversation = null;
                    }
                });
            }
        },
        
        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Just now';
            if (diff < 3600000) return Math.floor(diff / 60000) + 'm';
            if (diff < 86400000) return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
    }
}
</script>
@endpush