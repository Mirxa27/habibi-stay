<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AdminController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_hosts' => User::whereHas('properties')->count(),
            'total_properties' => Property::count(),
            'pending_verifications' => Property::where('is_verified', false)->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_properties = Property::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_properties'));
    }

    public function users(): View
    {
        $users = User::withCount(['properties', 'bookings'])
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function hosts(): View
    {
        $hosts = User::whereHas('properties')
            ->withCount(['properties', 'bookings'])
            ->latest()
            ->paginate(10);

        return view('admin.hosts.index', compact('hosts'));
    }

    public function editUser(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_admin' => 'boolean',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->is_admin = $validated['is_admin'] ?? false;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function deleteUser(User $user)
    {
        if ($user->getKey() === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }
} 