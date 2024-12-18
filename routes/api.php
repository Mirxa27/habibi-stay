<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ChannelManagerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public Property Routes
Route::get('/properties/search', [PropertyController::class, 'search']);
Route::get('/properties/featured', [PropertyController::class, 'featured']);
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/{property}', [PropertyController::class, 'show']);

// Protected Property Routes
Route::middleware(['auth:sanctum', 'throttle:property-actions'])->group(function () {
    // Property Management
    Route::post('/properties', [PropertyController::class, 'store']);
    Route::put('/properties/{property}', [PropertyController::class, 'update']);
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy']);
    
    // Property Media
    Route::post('/properties/{property}/images', [PropertyController::class, 'uploadImages']);
    Route::delete('/properties/{property}/images/{index}', [PropertyController::class, 'deleteImage']);
    
    // Property Settings
    Route::put('/properties/{property}/availability', [PropertyController::class, 'updateAvailability']);
    Route::put('/properties/{property}/pricing', [PropertyController::class, 'updatePricing']);
    Route::post('/properties/{property}/verify', [PropertyController::class, 'requestVerification']);
    
    // Property Analytics
    Route::get('/properties/{property}/analytics', [PropertyController::class, 'analytics']);
    Route::get('/properties/{property}/export', [PropertyController::class, 'export']);
});

// Channel Manager API Routes
Route::middleware(['auth:sanctum', 'throttle:30,1'])->prefix('channel-manager')->group(function () {
    Route::get('/calendar', [ChannelManagerController::class, 'calendar']);
    Route::patch('/properties/{property}/price', [ChannelManagerController::class, 'updatePrice']);
    Route::patch('/properties/{property}/availability', [ChannelManagerController::class, 'updateAvailability']);
    Route::post('/properties/{property}/sync', [ChannelManagerController::class, 'syncCalendar']);
});

// Booking API Routes
Route::middleware('auth:sanctum')->prefix('bookings')->group(function () {
    Route::get('/', [BookingController::class, 'index']);
    Route::post('/{property}', [BookingController::class, 'store']);
    Route::get('/{booking}', [BookingController::class, 'show']);
    Route::post('/{booking}/cancel', [BookingController::class, 'cancel']);
    Route::get('/{booking}/callback', [BookingController::class, 'callback'])->withoutMiddleware('auth:sanctum');
    Route::get('/{booking}/error', [BookingController::class, 'error'])->withoutMiddleware('auth:sanctum');
});

// Review API Routes
Route::middleware('auth:sanctum')->prefix('reviews')->group(function () {
    Route::get('/property/{property}', [ReviewController::class, 'propertyReviews']);
    Route::post('/{booking}', [ReviewController::class, 'store']);
    Route::put('/{review}', [ReviewController::class, 'update']);
    Route::delete('/{review}', [ReviewController::class, 'destroy']);
    Route::post('/{review}/report', [ReviewController::class, 'report']);
}); 