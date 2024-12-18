@props(['user'])

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Habibi Stay" height="40">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('properties.*') ? 'active' : '' }}" href="{{ route('properties.index') }}">
                        Properties
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        Contact
                    </a>
                </li>
            </ul>

            @auth
                <ul class="navbar-nav ms-auto">
                    @if($user->unreadNotifications->count() > 0)
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $user->unreadNotifications->count() }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @foreach($user->unreadNotifications as $notification)
                                    <li>
                                        <a class="dropdown-item" href="{{ $notification->data['action_url'] ?? '#' }}">
                                            {{ $notification->data['message'] }}
                                            <small class="d-block text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </a>
                                    </li>
                                @endforeach
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-center" href="{{ route('notifications.index') }}">
                                        View All Notifications
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ $user->profile_photo_url }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle"
                                 width="32"
                                 height="32">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if($user->isAdmin())
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>
                                        Admin Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bi bi-grid me-2"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.show') }}">
                                    <i class="bi bi-person me-2"></i>
                                    Profile
                                </a>
                            </li>
                            @if($user->isPropertyManager())
                                <li>
                                    <a class="dropdown-item" href="{{ route('properties.index') }}">
                                        <i class="bi bi-houses me-2"></i>
                                        My Properties
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('bookings.index') }}">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    My Bookings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('messages.index') }}">
                                    <i class="bi bi-chat-dots me-2"></i>
                                    Messages
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @else
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav> 