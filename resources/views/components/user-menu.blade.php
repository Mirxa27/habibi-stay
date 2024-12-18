@props(['user'])

<div class="nav-item dropdown">
    <a class="nav-link dropdown-toggle d-flex align-items-center" 
       href="#" 
       role="button" 
       data-bs-toggle="dropdown" 
       aria-expanded="false">
        <img src="{{ $user->profile_photo_url }}" 
             class="rounded-circle me-2"
             width="32"
             height="32"
             alt="{{ $user->name }}">
        <span class="d-none d-md-inline">{{ $user->name }}</span>
    </a>

    <ul class="dropdown-menu dropdown-menu-end">
        <li class="dropdown-header">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img src="{{ $user->profile_photo_url }}" 
                         class="rounded-circle"
                         width="48"
                         height="48"
                         alt="{{ $user->name }}">
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0">{{ $user->name }}</h6>
                    <small class="text-muted">{{ $user->email }}</small>
                </div>
            </div>
        </li>
        <li><hr class="dropdown-divider"></li>

        <!-- Dashboard -->
        <li>
            <a class="dropdown-item" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <!-- Property Management (for hosts) -->
        @if($user->isPropertyManager())
            <li>
                <a class="dropdown-item" href="{{ route('dashboard.properties') }}">
                    <i class="bi bi-houses me-2"></i> My Properties
                </a>
            </li>
        @endif

        <!-- Bookings -->
        <li>
            <a class="dropdown-item" href="{{ route('dashboard.bookings') }}">
                <i class="bi bi-calendar-check me-2"></i> My Bookings
            </a>
        </li>

        <!-- Messages -->
        <li>
            <a class="dropdown-item" href="{{ route('messages.index') }}">
                <i class="bi bi-chat-dots me-2"></i> Messages
                @if($user->unreadMessages()->count() > 0)
                    <span class="badge bg-danger rounded-pill ms-2">
                        {{ $user->unreadMessages()->count() }}
                    </span>
                @endif
            </a>
        </li>

        <!-- Notifications -->
        <li>
            <a class="dropdown-item" href="{{ route('notifications.index') }}">
                <i class="bi bi-bell me-2"></i> Notifications
                @if($user->unreadNotifications->count() > 0)
                    <span class="badge bg-danger rounded-pill ms-2">
                        {{ $user->unreadNotifications->count() }}
                    </span>
                @endif
            </a>
        </li>

        <!-- Wishlist -->
        <li>
            <a class="dropdown-item" href="{{ route('wishlist.index') }}">
                <i class="bi bi-heart me-2"></i> Wishlist
            </a>
        </li>

        <li><hr class="dropdown-divider"></li>

        <!-- Account Settings -->
        <li>
            <a class="dropdown-item" href="{{ route('profile.show') }}">
                <i class="bi bi-person-gear me-2"></i> Account Settings
            </a>
        </li>

        <!-- Help Center -->
        <li>
            <a class="dropdown-item" href="{{ route('help') }}">
                <i class="bi bi-question-circle me-2"></i> Help Center
            </a>
        </li>

        <!-- Admin Panel (for admins) -->
        @if($user->isAdmin())
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-shield-lock me-2"></i> Admin Panel
                </a>
            </li>
        @endif

        <li><hr class="dropdown-divider"></li>

        <!-- Logout -->
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </li>
    </ul>
</div>

@push('styles')
<style>
.dropdown-header {
    padding: 1rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.dropdown-menu {
    min-width: 280px;
    padding: 0;
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item {
    padding: 0.75rem 1rem;
}

.dropdown-item:active {
    background-color: var(--bs-primary);
}

.dropdown-item i {
    width: 1.25rem;
    text-align: center;
}

.dropdown-divider {
    margin: 0;
}
</style>
@endpush 