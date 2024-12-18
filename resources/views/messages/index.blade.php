<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Communicate with your guests and hosts
                </p>
            </div>

            <div class="bg-white shadow sm:rounded-lg">
                <div class="grid grid-cols-1 lg:grid-cols-3">
                    <!-- Conversation List -->
                    <div class="border-r border-gray-200">
                        <!-- Search -->
                        <div class="p-4 border-b border-gray-200">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-search text-gray-400"></i>
                                </div>
                                <input type="text" name="search" id="search" 
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm"
                                       placeholder="Search messages...">
                            </div>
                        </div>

                        <!-- Conversations -->
                        <div class="overflow-y-auto h-[calc(100vh-16rem)]">
                            @forelse($conversations as $conversation)
                                <a href="{{ route('messages.show', $conversation) }}" 
                                   class="block hover:bg-gray-50 {{ $conversation->id === $currentConversation?->id ? 'bg-gray-50' : '' }}">
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full" 
                                                         src="{{ $conversation->otherUser->profile_photo_url }}" 
                                                         alt="{{ $conversation->otherUser->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $conversation->otherUser->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 truncate">
                                                        {{ $conversation->lastMessage?->content ?? 'No messages yet' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-2 flex flex-col items-end">
                                                <div class="text-xs text-gray-500">
                                                    {{ $conversation->lastMessage?->created_at->diffForHumans() }}
                                                </div>
                                                @if($conversation->unreadCount > 0)
                                                    <div class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary text-white">
                                                        {{ $conversation->unreadCount }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-12">
                                    <i class="bi bi-chat-dots text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-sm text-gray-500">No conversations yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="lg:col-span-2">
                        @if($currentConversation)
                            <!-- Chat Header -->
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full" 
                                             src="{{ $currentConversation->otherUser->profile_photo_url }}" 
                                             alt="{{ $currentConversation->otherUser->name }}">
                                        <div class="ml-4">
                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ $currentConversation->otherUser->name }}
                                            </h2>
                                            @if($currentConversation->booking)
                                                <p class="text-sm text-gray-500">
                                                    Booking #{{ $currentConversation->booking->booking_number }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" class="text-gray-400 hover:text-gray-500">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages -->
                            <div class="p-4 space-y-4 overflow-y-auto h-[calc(100vh-24rem)]" id="messages">
                                @foreach($currentConversation->messages as $message)
                                    <div class="flex {{ $message->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="flex items-end">
                                            @if($message->user_id !== Auth::id())
                                                <img class="h-8 w-8 rounded-full mr-2" 
                                                     src="{{ $message->user->profile_photo_url }}" 
                                                     alt="{{ $message->user->name }}">
                                            @endif
                                            <div class="{{ $message->user_id === Auth::id() ? 'bg-primary text-white' : 'bg-gray-100 text-gray-900' }} rounded-lg px-4 py-2 max-w-lg">
                                                <p class="text-sm">{{ $message->content }}</p>
                                                <p class="text-xs {{ $message->user_id === Auth::id() ? 'text-white/75' : 'text-gray-500' }} mt-1">
                                                    {{ $message->created_at->format('g:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Message Input -->
                            <div class="p-4 border-t border-gray-200">
                                <form action="{{ route('messages.store', $currentConversation) }}" method="POST">
                                    @csrf
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1">
                                            <textarea name="content" rows="1" 
                                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm"
                                                      placeholder="Type your message..."></textarea>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <button type="submit" class="btn-primary">
                                                <i class="bi bi-send"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center">
                                    <i class="bi bi-chat-square-dots text-gray-400 text-6xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900">No conversation selected</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Choose a conversation from the list to start messaging
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Scroll to bottom of messages
        const messagesContainer = document.getElementById('messages');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Real-time updates with Laravel Echo
        window.Echo.private('conversation.{{ $currentConversation?->id }}')
            .listen('MessageSent', (e) => {
                const messagesContainer = document.getElementById('messages');
                const messageDiv = document.createElement('div');
                messageDiv.className = `flex ${e.message.user_id === {{ Auth::id() }} ? 'justify-end' : 'justify-start'}`;
                
                messageDiv.innerHTML = `
                    <div class="flex items-end">
                        ${e.message.user_id !== {{ Auth::id() }} ? `
                            <img class="h-8 w-8 rounded-full mr-2" 
                                 src="${e.message.user.profile_photo_url}" 
                                 alt="${e.message.user.name}">
                        ` : ''}
                        <div class="${e.message.user_id === {{ Auth::id() }} ? 'bg-primary text-white' : 'bg-gray-100 text-gray-900'} rounded-lg px-4 py-2 max-w-lg">
                            <p class="text-sm">${e.message.content}</p>
                            <p class="text-xs ${e.message.user_id === {{ Auth::id() }} ? 'text-white/75' : 'text-gray-500'} mt-1">
                                ${new Date(e.message.created_at).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })}
                            </p>
                        </div>
                    </div>
                `;

                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            });
    </script>
    @endpush
</x-app-layout> 