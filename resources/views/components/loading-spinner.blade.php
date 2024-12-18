@props([
    'size' => 'md',
    'color' => 'primary',
    'fullscreen' => false,
    'text' => null
])

@php
    $spinnerSize = match($size) {
        'sm' => 'spinner-border-sm',
        'lg' => 'spinner-border-lg',
        default => ''
    };

    $spinnerColor = match($color) {
        'primary' => 'text-primary',
        'secondary' => 'text-secondary',
        'success' => 'text-success',
        'danger' => 'text-danger',
        'warning' => 'text-warning',
        'info' => 'text-info',
        'light' => 'text-light',
        'dark' => 'text-dark',
        default => 'text-primary'
    };
@endphp

@if($fullscreen)
    <div class="loading-overlay">
        <div class="text-center">
            <div class="spinner-border {{ $spinnerSize }} {{ $spinnerColor }}" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            @if($text)
                <div class="mt-3 text-{{ $color }}">{{ $text }}</div>
            @endif
        </div>
    </div>
@else
    <div class="d-inline-block">
        <div class="spinner-border {{ $spinnerSize }} {{ $spinnerColor }}" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        @if($text)
            <span class="ms-2 text-{{ $color }}">{{ $text }}</span>
        @endif
    </div>
@endif

@if($fullscreen)
    @push('styles')
    <style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .spinner-border-lg {
        width: 3rem;
        height: 3rem;
    }
    </style>
    @endpush
@endif 