<x-app-layout>
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage users, properties, and platform settings
                </p>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-people text-2xl text-primary"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_users'] }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    {{ $stats['new_users_count'] }} new this month
                                </div>
                                <div class="text-sm {{ $stats['user_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="bi {{ $stats['user_growth'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                    {{ abs($stats['user_growth']) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Properties -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-houses text-2xl text-blue-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Properties</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_properties'] }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    {{ $stats['active_properties'] }} active properties
                                </div>
                                <div class="text-sm {{ $stats['property_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="bi {{ $stats['property_growth'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                    {{ abs($stats['property_growth']) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-calendar-check text-2xl text-green-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $stats['total_bookings'] }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    {{ $stats['pending_bookings'] }} pending
                                </div>
                                <div class="text-sm {{ $stats['booking_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="bi {{ $stats['booking_growth'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                    {{ abs($stats['booking_growth']) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="bi bi-currency-dollar text-2xl text-yellow-500"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                    <dd class="text-lg font-semibold text-gray-900">SAR {{ number_format($stats['total_revenue']) }}</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    This month
                                </div>
                                <div class="text-sm {{ $stats['revenue_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    <i class="bi {{ $stats['revenue_growth'] >= 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} mr-1"></i>
                                    {{ abs($stats['revenue_growth']) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 mb-8">
                <!-- Recent Users -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium text-gray-900">Recent Users</h2>
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-primary hover:text-primary-dark">
                                View all
                            </a>
                        </div>
                        <div class="flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200">
                                @foreach($recentUsers as $user)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-full" 
                                                     src="{{ $user->profile_photo_url }}" 
                                                     alt="{{ $user->name }}">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $user->name }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate">
                                                    {{ $user->email }}
                                                </p>
                                            </div>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->isPropertyManager() ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $user->isPropertyManager() ? 'Host' : 'Guest' }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Recent Properties -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-medium text-gray-900">Recent Properties</h2>
                            <a href="{{ route('admin.properties.index') }}" class="text-sm text-primary hover:text-primary-dark">
                                View all
                            </a>
                        </div>
                        <div class="flow-root">
                            <ul role="list" class="-my-5 divide-y divide-gray-200">
                                @foreach($recentProperties as $property)
                                    <li class="py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <img class="h-8 w-8 rounded-lg object-cover" 
                                                     src="{{ asset('storage/' . ($property->images[0] ?? 'properties/default.jpg')) }}" 
                                                     alt="{{ $property->title }}">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $property->title }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate">
                                                    {{ $property->address }}
                                                </p>
                                            </div>
                                            <div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->is_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $property->is_verified ? 'Verified' : 'Pending' }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow sm:rounded-lg mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <a href="{{ route('admin.users.create') }}" class="group block">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary hover:bg-gray-50">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-person-plus text-2xl text-gray-400 group-hover:text-primary"></i>
                                    <h3 class="ml-3 text-sm font-medium text-gray-900">Add User</h3>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Create a new user account
                                </p>
                            </div>
                        </a>

                        <a href="{{ route('admin.properties.verify') }}" class="group block">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary hover:bg-gray-50">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-check-circle text-2xl text-gray-400 group-hover:text-primary"></i>
                                    <h3 class="ml-3 text-sm font-medium text-gray-900">Verify Properties</h3>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Review and verify new properties
                                </p>
                            </div>
                        </a>

                        <a href="{{ route('admin.bookings.pending') }}" class="group block">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary hover:bg-gray-50">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-calendar-check text-2xl text-gray-400 group-hover:text-primary"></i>
                                    <h3 class="ml-3 text-sm font-medium text-gray-900">Pending Bookings</h3>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Review pending bookings
                                </p>
                            </div>
                        </a>

                        <a href="{{ route('admin.reports.index') }}" class="group block">
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-primary hover:bg-gray-50">
                                <div class="flex items-center mb-2">
                                    <i class="bi bi-graph-up text-2xl text-gray-400 group-hover:text-primary"></i>
                                    <h3 class="ml-3 text-sm font-medium text-gray-900">Reports</h3>
                                </div>
                                <p class="text-sm text-gray-500">
                                    View analytics and reports
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">System Status</h2>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">API Services</h3>
                            <ul class="mt-2 space-y-2">
                                <li class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">MyFatoorah</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Operational
                                    </span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Google Maps</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Operational
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Server Status</h3>
                            <ul class="mt-2 space-y-2">
                                <li class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">CPU Usage</span>
                                    <span class="text-sm text-gray-900">{{ $systemStatus['cpu_usage'] }}%</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Memory Usage</span>
                                    <span class="text-sm text-gray-900">{{ $systemStatus['memory_usage'] }}%</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Queue Status</h3>
                            <ul class="mt-2 space-y-2">
                                <li class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Failed Jobs</span>
                                    <span class="text-sm text-gray-900">{{ $systemStatus['failed_jobs'] }}</span>
                                </li>
                                <li class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Pending Jobs</span>
                                    <span class="text-sm text-gray-900">{{ $systemStatus['pending_jobs'] }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 