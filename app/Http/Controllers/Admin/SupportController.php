<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Conversation;
use App\Models\KnowledgeBase;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    // Tickets
    public function tickets(Request $request)
    {
        $query = Ticket::with(['user', 'assignedTo']);
        
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->priority && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        
        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);
        $agents = User::where('role', 'agent')->get();
        
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'avg_response' => round(Ticket::avg('response_time') / 3600, 1)
        ];
        
        return view('admin.support.tickets', compact('tickets', 'agents', 'stats'));
    }
    
    public function ticketStats()
    {
        return response()->json([
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('status', 'resolved')->count(),
            'avg_response' => round(Ticket::avg('response_time') / 3600, 1)
        ]);
    }
    
    public function showTicket($id)
    {
        $ticket = Ticket::with(['user', 'assignedTo', 'messages.user'])->findOrFail($id);
        return response()->json($ticket);
    }
    
    public function storeTicket(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'description' => 'required|string'
        ]);
        
        $ticket = Ticket::create([
            ...$validated,
            'user_id' => auth()->id(),
            'ticket_number' => 'TKT-' . str_pad(Ticket::count() + 1, 6, '0', STR_PAD_LEFT)
        ]);
        
        return response()->json(['success' => true, 'ticket' => $ticket]);
    }
    
    public function updateTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->all());
        return response()->json(['success' => true]);
    }
    
    public function replyTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        $message = $ticket->messages()->create([
            'user_id' => auth()->id(),
            'content' => $request->message,
            'is_agent' => true
        ]);
        
        $ticket->update(['status' => 'in_progress']);
        
        return response()->json(['success' => true, 'message' => $message]);
    }
    
    public function destroyTicket($id)
    {
        Ticket::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
    
    // Conversations (Live Chat)
    public function conversations()
    {
        $conversations = Conversation::with(['user', 'messages'])->get();
        return view('admin.support.conversations', compact('conversations'));
    }
    
    public function sendMessage(Request $request)
    {
        $message = ConversationMessage::create([
            'conversation_id' => $request->conversation_id,
            'user_id' => auth()->id(),
            'content' => $request->message,
            'is_agent' => true
        ]);
        
        broadcast(new NewMessage($message))->toOthers();
        
        return response()->json(['success' => true, 'message' => $message]);
    }
    
    public function markAsRead(Request $request)
    {
        $conversation = Conversation::find($request->conversation_id);
        $conversation->unread_count = 0;
        $conversation->save();
        
        return response()->json(['success' => true]);
    }
    
    public function endChat(Request $request)
    {
        $conversation = Conversation::find($request->conversation_id);
        $conversation->status = 'ended';
        $conversation->save();
        
        return response()->json(['success' => true]);
    }
    
    // Knowledge Base
    public function knowledgeBase(Request $request)
    {
        $query = KnowledgeBase::with('category');
        
        if ($request->category && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }
        
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        $articles = $query->paginate(12);
        $categories = KnowledgeBaseCategory::all();
        
        $stats = [
            'total' => KnowledgeBase::count(),
            'published' => KnowledgeBase::where('status', 'published')->count(),
            'drafts' => KnowledgeBase::where('status', 'draft')->count(),
            'views' => KnowledgeBase::sum('views')
        ];
        
        return view('admin.support.knowledge-base', compact('articles', 'categories', 'stats'));
    }
    
    public function storeArticle(Request $request)
    {
        $article = KnowledgeBase::create($request->all());
        return response()->json(['success' => true, 'article' => $article]);
    }
    
    public function updateArticle(Request $request, $id)
    {
        $article = KnowledgeBase::findOrFail($id);
        $article->update($request->all());
        return response()->json(['success' => true]);
    }
    
    public function destroyArticle($id)
    {
        KnowledgeBase::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
    
    // Announcements
    public function announcements(Request $request)
    {
        $query = Announcement::query();
        
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        $announcements = $query->orderBy('published_at', 'desc')->paginate(10);
        
        $stats = [
            'total' => Announcement::count(),
            'active' => Announcement::where('status', 'active')->count(),
            'scheduled' => Announcement::where('status', 'scheduled')->count(),
            'read_rate' => round(Announcement::avg('read_rate') ?? 0)
        ];
        
        return view('admin.support.announcements', compact('announcements', 'stats'));
    }
    
    public function storeAnnouncement(Request $request)
    {
        $announcement = Announcement::create($request->all());
        return response()->json(['success' => true, 'announcement' => $announcement]);
    }
    
    public function updateAnnouncement(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update($request->all());
        return response()->json(['success' => true]);
    }
    
    public function destroyAnnouncement($id)
    {
        Announcement::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}