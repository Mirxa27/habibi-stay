<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        // Get current month and last month revenue
        $currentMonthRevenue = Booking::where('status', 'confirmed')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total_price');

        $lastMonthRevenue = Booking::where('status', 'confirmed')
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total_price');

        // Calculate revenue growth
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : 100;

        // Get current month and last month booking counts
        $currentMonthBookings = Booking::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $lastMonthBookings = Booking::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        // Calculate bookings growth
        $bookingsGrowth = $lastMonthBookings > 0 
            ? (($currentMonthBookings - $lastMonthBookings) / $lastMonthBookings) * 100 
            : 100;

        // Get properties query based on user role
        $propertiesQuery = $user->isAdmin() 
            ? Property::query() 
            : Property::where('property_manager_id', $user->id);

        // Get active properties count
        $activeProperties = $propertiesQuery->where('is_active', true)->count();

        // Get average rating
        $averageRating = Review::whereIn('property_id', $propertiesQuery->pluck('id'))
            ->avg('rating') ?? 0;

        // Get recent bookings
        $recentBookings = Booking::with(['property', 'user'])
            ->whereIn('property_id', $propertiesQuery->pluck('id'))
            ->latest()
            ->take(5)
            ->get();

        // Get properties
        $properties = $propertiesQuery->with(['reviews', 'bookings'])
            ->latest()
            ->take(6)
            ->get();

        // Get upcoming check-ins
        $upcomingCheckins = Booking::with('property')
            ->whereIn('property_id', $propertiesQuery->pluck('id'))
            ->where('status', 'confirmed')
            ->where('check_in', '>=', $now)
            ->orderBy('check_in')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'currentMonthRevenue',
            'revenueGrowth',
            'currentMonthBookings',
            'bookingsGrowth',
            'activeProperties',
            'averageRating',
            'recentBookings',
            'properties',
            'upcomingCheckins'
        ));
    }
}
