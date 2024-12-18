<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-light">
    <div class="min-vh-100 d-flex flex-column">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" height="32">
                </a>

                <!-- Mobile Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Links -->
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" 
                               href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('properties.*') ? 'active' : '' }}" 
                               href="{{ route('properties.index') }}">Properties</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" 
                               href="{{ route('about') }}">About</a>
                        </li>
                    </ul>

                    <!-- Right Side Navigation -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('properties.create') ? 'active' : '' }}" 
                                   href="{{ route('properties.create') }}">
                                    <i class="bi bi-plus-lg"></i> List Property
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ Auth::user()->profile_photo_url }}" 
                                         alt="{{ Auth::user()->name }}"
                                         class="rounded-circle"
                                         width="32"
                                         height="32">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="bi bi-person me-2"></i> Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow-1 py-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-light py-5 mt-auto">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <h5 class="mb-4">{{ config('app.name') }}</h5>
                        <p class="text-muted">
                            Your trusted platform for finding and booking luxury accommodations in Kuwait.
                            Experience the finest properties with world-class amenities and exceptional service.
                        </p>
                        <div class="social-links">
                            <a href="#" class="text-muted me-3"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-muted me-3"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="text-muted me-3"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="text-muted"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <h6 class="mb-4">Quick Links</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('home') }}" class="text-muted">Home</a></li>
                            <li><a href="{{ route('properties.index') }}" class="text-muted">Properties</a></li>
                            <li><a href="{{ route('about') }}" class="text-muted">About Us</a></li>
                            <li><a href="{{ route('contact') }}" class="text-muted">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2">
                        <h6 class="mb-4">Support</h6>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-muted">Help Center</a></li>
                            <li><a href="#" class="text-muted">Safety Information</a></li>
                            <li><a href="#" class="text-muted">Cancellation Options</a></li>
                            <li><a href="#" class="text-muted">Report Issue</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="mb-4">Newsletter</h6>
                        <p class="text-muted mb-4">Subscribe to our newsletter for the latest updates and exclusive offers.</p>
                        <form class="mb-3">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Enter your email">
                                <button class="btn btn-primary" type="submit">Subscribe</button>
                            </div>
                        </form>
                        <div class="payment-methods">
                            <img src="{{ asset('images/payment-methods.png') }}" alt="Payment Methods" class="img-fluid">
                        </div>
                    </div>
                </div>
                <hr class="my-4 border-secondary">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item">
                                <a href="#" class="text-muted">Terms</a>
                            </li>
                            <li class="list-inline-item">
                                <span class="text-muted">·</span>
                            </li>
                            <li class="list-inline-item">
                                <a href="#" class="text-muted">Privacy</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- MyFatoorah Payment Integration -->
    @if(config('services.myfatoorah.enabled'))
        <script src="https://portal.myfatoorah.com/Scripts/api/payment/v2/payment.min.js"></script>
    @endif

    <!-- Google Maps Integration -->
    @if(config('services.google.maps_api_key'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"></script>
    @endif

    @stack('modals')
    @stack('scripts')
</body>
</html> 