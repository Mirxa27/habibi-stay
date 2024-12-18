<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Manage Hosts</h1>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Hosts Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Properties</th>
                                <th>Active Bookings</th>
                                <th>Total Revenue</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hosts as $host)
                                <tr>
                                    <td>{{ $host->name }}</td>
                                    <td>{{ $host->email }}</td>
                                    <td>
                                        <a href="{{ route('admin.hosts.properties', $host) }}" class="text-decoration-none">
                                            {{ $host->properties_count }} properties
                                        </a>
                                    </td>
                                    <td>{{ $host->active_bookings_count ?? 0 }}</td>
                                    <td>SAR {{ number_format($host->total_revenue ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $host->is_verified ? 'success' : 'warning' }}">
                                            {{ $host->is_verified ? 'Verified' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('admin.hosts.show', $host) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @if(!$host->is_verified)
                                                <form action="{{ route('admin.hosts.verify', $host) }}" 
                                                      method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.hosts.suspend', $host) }}" 
                                                  method="POST"
                                                  onsubmit="return confirm('Are you sure you want to suspend this host?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pause-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No hosts found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $hosts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 