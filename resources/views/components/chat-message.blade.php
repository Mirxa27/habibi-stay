@props(['message', 'isOwn' => false])

<div class="message {{ $isOwn ? 'message-own' : 'message-other' }} mb-3">
    <div class="d-flex {{ $isOwn ? 'justify-content-end' : 'justify-content-start' }}">
        @unless($isOwn)
            <img src="{{ $message->sender->profile_photo_url }}" 
                 class="rounded-circle me-2"
                 width="32"
                 height="32"
                 alt="{{ $message->sender->name }}">
        @endunless

        <div class="message-content {{ $isOwn ? 'bg-primary text-white' : 'bg-light' }} 
                    rounded p-3 shadow-sm"
             style="max-width: 75%;">
            @unless($isOwn)
                <div class="small fw-bold mb-1">{{ $message->sender->name }}</div>
            @endunless
            
            <div class="message-text">{{ $message->content }}</div>
            
            @if($message->attachments && count($message->attachments) > 0)
                <div class="message-attachments mt-2">
                    @foreach($message->attachments as $attachment)
                        <div class="attachment p-2 bg-white rounded mt-1">
                            <i class="bi bi-paperclip me-1"></i>
                            <a href="{{ Storage::url($attachment) }}" 
                               target="_blank"
                               class="{{ $isOwn ? 'text-white' : 'text-primary' }}">
                                {{ basename($attachment) }}
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
            
            <div class="message-meta small {{ $isOwn ? 'text-white-50' : 'text-muted' }} mt-1">
                {{ $message->created_at->format('g:i A') }}
                @if($message->read_at && $isOwn)
                    <i class="bi bi-check2-all ms-1"></i>
                @endif
            </div>
        </div>

        @if($isOwn)
            <img src="{{ $message->sender->profile_photo_url }}" 
                 class="rounded-circle ms-2"
                 width="32"
                 height="32"
                 alt="{{ $message->sender->name }}">
        @endif
    </div>
</div>

@push('styles')
<style>
.message-content {
    position: relative;
}
.message-own .message-content::after {
    content: '';
    position: absolute;
    right: -8px;
    top: 10px;
    border: 8px solid transparent;
    border-left-color: var(--bs-primary);
}
.message-other .message-content::after {
    content: '';
    position: absolute;
    left: -8px;
    top: 10px;
    border: 8px solid transparent;
    border-right-color: var(--bs-light);
}
</style>
@endpush 