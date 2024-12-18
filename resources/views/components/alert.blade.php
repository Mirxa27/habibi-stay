@props([
    'type' => 'info',
    'dismissible' => true,
    'icon' => true,
    'title' => null
])

@php
    $alertClass = match($type) {
        'success' => 'alert-success',
        'danger', 'error' => 'alert-danger',
        'warning' => 'alert-warning',
        default => 'alert-info'
    };

    $iconClass = match($type) {
        'success' => 'bi-check-circle',
        'danger', 'error' => 'bi-exclamation-circle',
        'warning' => 'bi-exclamation-triangle',
        default => 'bi-info-circle'
    };
@endphp

<div {{ $attributes->merge(['class' => "alert $alertClass"]) }} 
     role="alert"
     x-data="{ show: true }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95">
    
    <div class="d-flex align-items-center">
        @if($icon)
            <i class="bi {{ $iconClass }} fs-4 me-2"></i>
        @endif

        <div class="flex-grow-1">
            @if($title)
                <h5 class="alert-heading mb-1">{{ $title }}</h5>
            @endif
            <div class="alert-message">
                {{ $slot }}
            </div>
        </div>

        @if($dismissible)
            <button type="button" 
                    class="btn-close" 
                    data-bs-dismiss="alert" 
                    aria-label="Close"
                    @click="show = false"></button>
        @endif
    </div>
</div>

@push('styles')
<style>
.alert {
    border: none;
    border-radius: 0.5rem;
    padding: 1rem;
    margin-bottom: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.alert-success {
    color: #0f5132;
    background-color: #d1e7dd;
}

.alert-danger {
    color: #842029;
    background-color: #f8d7da;
}

.alert-warning {
    color: #664d03;
    background-color: #fff3cd;
}

.alert-info {
    color: #055160;
    background-color: #cff4fc;
}

.alert .btn-close {
    padding: 1.05rem;
    margin: -0.5rem -0.5rem -0.5rem 0.5rem;
}

.alert .bi {
    line-height: 1;
}

.alert-heading {
    color: inherit;
    font-size: 1.1rem;
}

/* Animation classes for Alpine.js transitions */
.transition {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

.duration-200 {
    transition-duration: 200ms;
}

.duration-300 {
    transition-duration: 300ms;
}

.transform {
    transform: translateX(var(--tw-translate-x)) translateY(var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
}

.scale-95 {
    --tw-scale-x: .95;
    --tw-scale-y: .95;
}

.scale-100 {
    --tw-scale-x: 1;
    --tw-scale-y: 1;
}

.opacity-0 {
    opacity: 0;
}

.opacity-100 {
    opacity: 1;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds if they're not error/danger type
    const alerts = document.querySelectorAll('.alert:not(.alert-danger)');
    alerts.forEach(alert => {
        setTimeout(() => {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            }
        }, 5000);
    });
});
</script>
@endpush 