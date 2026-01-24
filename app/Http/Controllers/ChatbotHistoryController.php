<?php

namespace App\Http\Controllers;

use App\Models\ChatbotSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotHistoryController extends Controller
{
    /**
     * Get chat history list for the current user.
     * Limit to latest 5 sessions as requested.
     */
    public function index()
    {
        $userId = Auth::id();
        $histories = ChatbotSession::where('user_id', $userId)
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get(['id', 'title', 'updated_at']);

        // Map to frontend expected format
        $mapped = $histories->map(function ($session) {
            return [
                'id' => $session->id,
                'title' => $session->title ?? 'Percakapan Baru',
                'timestamp' => $session->updated_at->timestamp * 1000,
                // n8n identifiers if needed, but we use DB ID mainly now
                'sessionId' => $session->id,
                'conversationId' => $session->id,
            ];
        });

        return response()->json($mapped);
    }

    /**
     * Get messages for a specific session.
     */
    public function show($id)
    {
        $userId = Auth::id();
        $session = ChatbotSession::with([
            'messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }
        ])
            ->where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json([
            'id' => $session->id,
            'title' => $session->title,
            'timestamp' => $session->updated_at->timestamp * 1000,
            'messages' => $session->messages->map(function ($msg) {
                return [
                    'role' => $msg->role,
                    'content' => $msg->content,
                    'created_at' => $msg->created_at->timestamp * 1000
                ];
            })
        ]);
    }

    /**
     * Delete a session.
     */
    public function destroy($id)
    {
        $userId = Auth::id();
        $session = ChatbotSession::where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $session->delete();

        return response()->json(['success' => true]);
    }
}
