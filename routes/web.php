<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ChannelManagerController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Property Management Routes
Route::prefix('properties')->middleware(['web'])->group(function () {
    // Public Routes
    Route::get('/', [PropertyController::class, 'index'])->name('properties.index');
    Route::get('/filter', [PropertyController::class, 'filter'])->name('properties.filter');
    Route::get('/{property}', [PropertyController::class, 'show'])
        ->name('properties.show')
        ->middleware('cache.headers:public;max_age=3600');
    Route::get('/{property}/similar', [PropertyController::class, 'getSimilarProperties'])
        ->name('properties.similar')
        ->middleware('cache.headers:public;max_age=3600');

    // Authenticated Routes with Rate Limiting
    Route::middleware(['auth', 'throttle:60,1'])->group(function () {
        // Property CRUD Operations
        Route::get('/create', [PropertyController::class, 'create'])->name('properties.create');
        Route::post('/', [PropertyController::class, 'store'])
            ->middleware('verified')
            ->name('properties.store');
        Route::get('/{property}/edit', [PropertyController::class, 'edit'])
            ->middleware('can:update,property')
            ->name('properties.edit');
        Route::put('/{property}', [PropertyController::class, 'update'])
            ->middleware('can:update,property')
            ->name('properties.update');
        Route::delete('/{property}', [PropertyController::class, 'destroy'])
            ->middleware('can:delete,property')
            ->name('properties.destroy');

        // Property Media Management
        Route::delete('/{property}/images/{index}', [PropertyController::class, 'deleteImage'])
            ->middleware('can:update,property')
            ->name('properties.deleteImage');
        Route::post('/{property}/images', [PropertyController::class, 'uploadImages'])
            ->middleware('can:update,property')
            ->name('properties.uploadImages');

        // Property Settings and Features
        Route::post('/{property}/featured', [PropertyController::class, 'toggleFeatured'])
            ->middleware(['can:update,property', 'verified'])
            ->name('properties.toggleFeatured');
        Route::put('/{property}/availability', [PropertyController::class, 'updateAvailability'])
            ->middleware('can:update,property')
            ->name('properties.updateAvailability');
        Route::put('/{property}/pricing', [PropertyController::class, 'updatePricing'])
            ->middleware('can:update,property')
            ->name('properties.updatePricing');
        
        // Property Analytics and Export
        Route::get('/{property}/analytics', [PropertyController::class, 'analytics'])
            ->middleware('can:view,property')
            ->name('properties.analytics');
        Route::get('/{property}/export', [PropertyController::class, 'export'])
            ->middleware('can:view,property')
            ->name('properties.export');

        // Property Verification
        Route::post('/{property}/verify', [PropertyController::class, 'requestVerification'])
            ->middleware('can:update,property')
            ->name('properties.requestVerification');
        
        // Bulk Operations (Admin Only)
        Route::post('/bulk-action', [PropertyController::class, 'bulkAction'])
            ->middleware(['admin'])
            ->name('properties.bulkAction');
    });

    // Channel Manager Routes with Special Rate Limiting
    Route::middleware(['auth', 'throttle:30,1'])->group(function () {
        Route::patch('/{property}/price', [ChannelManagerController::class, 'updatePrice'])
            ->middleware('can:update,property')
            ->name('properties.updatePrice');
        Route::patch('/{property}/availability', [ChannelManagerController::class, 'updateAvailability'])
            ->middleware('can:update,property')
            ->name('properties.updateAvailability');
        Route::post('/{property}/sync', [ChannelManagerController::class, 'syncCalendar'])
            ->middleware('can:update,property')
            ->name('properties.syncCalendar');
    });

    // Property Messaging Routes
    Route::middleware(['auth', 'throttle:20,1'])->group(function () {
        Route::post('/{property}/inquire', [MessageController::class, 'create'])
            ->name('messages.create');
        Route::get('/{property}/messages', [MessageController::class, 'index'])
            ->middleware('can:view,property')
            ->name('messages.index');
    });
});

// Booking routes
Route::prefix('bookings')->middleware('auth')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/create/{property}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/{property}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/{booking}/callback', [BookingController::class, 'callback'])->name('bookings.callback');
    Route::get('/{booking}/error', [BookingController::class, 'error'])->name('bookings.error');
});

// Review routes
Route::prefix('reviews')->middleware('auth')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/create/{booking}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/{booking}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/{review}/report', [ReviewController::class, 'report'])->name('reviews.report');
});

// Dashboard routes
Route::prefix('dashboard')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/properties', [DashboardController::class, 'properties'])->name('dashboard.properties');
    Route::get('/bookings', [DashboardController::class, 'bookings'])->name('dashboard.bookings');
    Route::get('/reviews', [DashboardController::class, 'reviews'])->name('dashboard.reviews');
    Route::get('/calendar', [DashboardController::class, 'calendar'])->name('dashboard.calendar');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('dashboard.reports');
});

Route::middleware(['auth'])->group(function () {
    // Investor Routes
    Route::get('/investor', [InvestorController::class, 'index'])->name('investor.index');
    Route::get('/investor/opportunities', [InvestorController::class, 'opportunities'])->name('investor.opportunities');
    Route::get('/investor/portfolio', [InvestorController::class, 'portfolio'])->name('investor.portfolio');

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Host Management
        Route::get('/hosts', [AdminController::class, 'hosts'])->name('hosts');
        Route::get('/hosts/{user}', [AdminController::class, 'showHost'])->name('hosts.show');
        Route::get('/hosts/{user}/properties', [AdminController::class, 'hostProperties'])->name('hosts.properties');
        Route::post('/hosts/{user}/verify', [AdminController::class, 'verifyHost'])->name('hosts.verify');
        Route::put('/hosts/{user}/suspend', [AdminController::class, 'suspendHost'])->name('hosts.suspend');
    });

    // Messaging Routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/properties/{property}/inquire', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::patch('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
    Route::patch('/conversations/{conversation}/archive', [MessageController::class, 'archive'])->name('conversations.archive');
    Route::patch('/conversations/{conversation}/unarchive', [MessageController::class, 'unarchive'])->name('conversations.unarchive');

    // Channel Manager Routes
    Route::prefix('channel-manager')->name('channel-manager.')->group(function () {
        Route::get('/', [ChannelManagerController::class, 'index'])->name('index');
        Route::get('/calendar', [ChannelManagerController::class, 'calendar'])->name('calendar');
        Route::get('/pricing', [ChannelManagerController::class, 'pricing'])->name('pricing');
        Route::get('/bookings', [ChannelManagerController::class, 'bookings'])->name('bookings');
        Route::get('/content', [ChannelManagerController::class, 'content'])->name('content');
    });
});

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// API Documentation
Route::get('/api/docs', function () {
    return view('api.docs');
})->name('api.docs');
