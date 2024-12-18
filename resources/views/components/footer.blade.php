<footer class="bg-light mt-auto py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h5 class="mb-4">Habibi Stay</h5>
                <p class="text-muted">
                    Your trusted platform for finding and booking luxury accommodations in Kuwait.
                    Experience the finest properties with world-class amenities and exceptional service.
                </p>
                <div class="social-links">
                    <a href="#" class="text-muted me-3">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="text-muted me-3">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" class="text-muted me-3">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="text-muted">
                        <i class="bi bi-linkedin"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="mb-4">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('properties.index') }}" class="text-muted text-decoration-none">Properties</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('about') }}" class="text-muted text-decoration-none">About Us</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('contact') }}" class="text-muted text-decoration-none">Contact</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="mb-4">For Property Managers</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ route('properties.create') }}" class="text-muted text-decoration-none">List Property</a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Dashboard</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Terms for Hosts</a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-muted text-decoration-none">Community Guidelines</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
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

        <hr class="my-4">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-muted mb-0">
                    © {{ date('Y') }} Habibi Stay. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                    </li>
                    <li class="list-inline-item mx-3">
                        <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-muted text-decoration-none">FAQs</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer> 