@props(['notifications', 'limit' => null])

<div class="notification-list">
    @forelse($notifications as $notification)
        <div class="notification-item card mb-2 {{ $notification->read_at ? 'bg-light' : 'border-primary' }}"
             data-notification-id="{{ $notification->id }}">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="notification-icon">
                        @switch($notification->type)
                            @case('App\Notifications\BookingConfirmationNotification')
                                <i class="bi bi-calendar-check text-success"></i>
                                @break
                            @case('App\Notifications\NewMessageNotification')
                                <i class="bi bi-chat-dots text-primary"></i>
                                @break
                            @case('App\Notifications\NewReviewNotification')
                                <i class="bi bi-star text-warning"></i>
                                @break
                            @default
                                <i class="bi bi-bell text-secondary"></i>
                        @endswitch
                    </div>
                    <small class="text-muted">
                        {{ $notification->created_at->diffForHumans() }}
                    </small>
                </div>

                <div class="notification-content">
                    <p class="mb-1">{{ $notification->data['message'] }}</p>
                    @if(isset($notification->data['action_text']) && isset($notification->data['action_url']))
                        <a href="{{ $notification->data['action_url'] }}" 
                           class="btn btn-sm btn-outline-primary">
                            {{ $notification->data['action_text'] }}
                        </a>
                    @endif
                </div>

                @unless($notification->read_at)
                    <div class="notification-actions mt-2 text-end">
                        <form action="{{ route('notifications.mark-as-read', $notification) }}" 
                              method="POST" 
                              class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link text-muted p-0">
                                Mark as read
                            </button>
                        </form>
                    </div>
                @endunless
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-4">
            <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
            <p class="mb-0">No notifications yet</p>
        </div>
    @endforelse

    @if($notifications->count() > 0)
        <div class="text-center mt-3">
            @if($limit && $notifications->count() >= $limit)
                <a href="{{ route('notifications.index') }}" class="btn btn-link">
                    View All Notifications
                </a>
            @endif

            @if($notifications->whereNull('read_at')->count() > 0)
                <form action="{{ route('notifications.mark-all-as-read') }}" 
                      method="POST" 
                      class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>
    @endif
</div>

@push('styles')
<style>
.notification-item {
    transition: background-color 0.3s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: #f8f9fa;
}

.notification-icon i {
    font-size: 1.2rem;
}

.notification-actions button:hover {
    text-decoration: underline !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle notification click
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', async function(e) {
            // Don't trigger if clicking on a button or link
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A') {
                return;
            }

            const notificationId = this.dataset.notificationId;
            const actionUrl = this.querySelector('a')?.href;

            try {
                // Mark as read
                await fetch(`/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                // Navigate to action URL if exists
                if (actionUrl) {
                    window.location.href = actionUrl;
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        });
    });
});
</script>
@endpush 