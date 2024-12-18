@props([
    'id',
    'title',
    'size' => 'md',
    'closeButton' => true,
    'staticBackdrop' => false,
    'centered' => true,
    'scrollable' => false,
    'footer' => null
])

@php
    $modalSize = match($size) {
        'sm' => 'modal-sm',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        default => ''
    };

    $modalOptions = [];
    if ($staticBackdrop) {
        $modalOptions[] = 'data-bs-backdrop="static"';
        $modalOptions[] = 'data-bs-keyboard="false"';
    }
@endphp

<div class="modal fade" 
     id="{{ $id }}" 
     tabindex="-1" 
     aria-labelledby="{{ $id }}Label" 
     aria-hidden="true"
     {!! $modalOptions ? implode(' ', $modalOptions) : '' !!}>
    <div class="modal-dialog {{ $modalSize }} {{ $centered ? 'modal-dialog-centered' : '' }} {{ $scrollable ? 'modal-dialog-scrollable' : '' }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                @if($closeButton)
                    <button type="button" 
                            class="btn-close" 
                            data-bs-dismiss="modal" 
                            aria-label="Close"></button>
                @endif
            </div>
            
            <div class="modal-body">
                {{ $slot }}
            </div>

            @if($footer)
                <div class="modal-footer">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.modal-content {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-backdrop.show {
    opacity: 0.7;
}

/* Animation */
.modal.fade .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease-in-out;
}

.modal.show .modal-dialog {
    transform: scale(1);
}

/* Scrollbar styling for scrollable modals */
.modal-dialog-scrollable .modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.modal-dialog-scrollable .modal-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('{{ $id }}');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            // Focus first input if exists
            const firstInput = this.querySelector('input, textarea, select');
            if (firstInput) {
                firstInput.focus();
            }
        });

        // Handle form submission with enter key
        modal.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                const submitButton = this.querySelector('[type="submit"]');
                if (submitButton && !submitButton.disabled) {
                    e.preventDefault();
                    submitButton.click();
                }
            }
        });

        // Reset form on modal close if exists
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
        });
    }
});
</script>
@endpush 