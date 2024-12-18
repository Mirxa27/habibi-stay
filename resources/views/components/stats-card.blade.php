@props(['title', 'value', 'icon', 'color' => 'primary', 'percentage' => null, 'trend' => null])

<div class="card stats-card h-100">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h6 class="text-muted mb-1">{{ $title }}</h6>
                <h3 class="mb-0">{{ $value }}</h3>
                
                @if($percentage !== null)
                    <div class="mt-2">
                        <span class="text-{{ $trend === 'up' ? 'success' : 'danger' }} me-2">
                            <i class="bi bi-arrow-{{ $trend === 'up' ? 'up' : 'down' }}"></i>
                            {{ $percentage }}%
                        </span>
                        <span class="text-muted">vs last month</span>
                    </div>
                @endif
            </div>
            <div class="col-auto">
                <div class="icon-shape bg-{{ $color }} text-white rounded-circle p-3">
                    <i class="bi bi-{{ $icon }} fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div> 