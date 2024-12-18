<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use App\Notifications\NewMessageNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class MessageController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $conversations = Conversation::where('guest_id', $user->id)
            ->orWhere('host_id', $user->id)
            ->with(['property', 'guest', 'host', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(10);

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        
        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        $conversation->markAllAsRead(Auth::id());

        return view('messages.show', compact('conversation', 'messages'));
    }

    public function create(Request $request, Property $property)
    {
        $host = $property->user;
        
        // Check if conversation already exists
        $conversation = Conversation::where(function($query) use ($property, $host) {
            $query->where('property_id', $property->id)
                ->where('guest_id', Auth::id())
                ->where('host_id', $host->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'property_id' => $property->id,
                'guest_id' => Auth::id(),
                'host_id' => $host->id,
                'subject' => "Inquiry about {$property->title}",
                'last_message_at' => now(),
            ]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    public function store(Request $request, Conversation $conversation)
    {
        $this->authorize('reply', $conversation);
        
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Determine recipient
        $recipient = Auth::id() === $conversation->guest_id 
            ? $conversation->host 
            : $conversation->guest;

        // Broadcast event for real-time updates
        broadcast(new MessageSent($message))->toOthers();
        
        // Send notification
        $recipient->notify(new NewMessageNotification($message));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message->load('sender'),
                'conversation' => $conversation->fresh(),
            ]);
        }

        return back()->with('success', 'Message sent successfully.');
    }

    public function markAsRead(Request $request, Message $message)
    {
        $this->authorize('markAsRead', $message);
        
        $message->markAsRead();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function archive(Conversation $conversation)
    {
        $this->authorize('archive', $conversation);
        
        $conversation->update(['is_archived' => true]);

        return back()->with('success', 'Conversation archived successfully.');
    }

    public function unarchive(Conversation $conversation)
    {
        $this->authorize('archive', $conversation);
        
        $conversation->update(['is_archived' => false]);

        return back()->with('success', 'Conversation unarchived successfully.');
    }
}
