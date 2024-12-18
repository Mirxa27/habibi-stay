<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProperties = Property::with('user')
            ->where('is_verified', true)
            ->where('is_available', true)
            ->latest()
            ->take(6)
            ->get();

        return view('home.index', compact('featuredProperties'));
    }

    public function about()
    {
        return view('home.about');
    }
}
