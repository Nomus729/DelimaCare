<?php

namespace App\Http\Controllers;

use App\Models\ConsultationMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    // --- SISI PASIEN ---
    public function loadMessages()
    {
        $username = Auth::user()->username;

        $messages = ConsultationMessage::where('username', $username)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'sender' => $msg->sender,
                    'type' => $msg->type,
                    'text' => $msg->message,
                    'time' => $msg->created_at->format('H:i')
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $message = ConsultationMessage::create([
            'username' => Auth::user()->username,
            'sender' => $request->sender ?? 'user',
            'type' => $request->type ?? 'text',
            'message' => $request->message,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json(['success' => true, 'message' => $message]);
    }

    // --- SISI ADMIN ---
    public function getChatUsers()
    {
        // Ambil semua username unik yang pernah chat
        $usernames = ConsultationMessage::select('username')->distinct()->pluck('username');

        // Ambil info user tersebut dan sertakan last message info
        $users = \App\Models\User::whereIn('username', $usernames)->get()->map(function($user) {
            $lastMsg = ConsultationMessage::where('username', $user->username)->latest()->first();
            $unreadCount = ConsultationMessage::where('username', $user->username)
                ->where('sender', 'user')
                ->where('is_read', false)
                ->count();

            return [
                'id' => $user->username,
                'name' => $user->username,
                'last_message' => $lastMsg ? \Illuminate\Support\Str::limit($lastMsg->message, 30) : '',
                'time' => $lastMsg ? $lastMsg->created_at->format('H:i') : '',
                'last_time_obj' => $lastMsg ? $lastMsg->created_at : \Carbon\Carbon::create(1970, 1, 1),
                'unread_count' => $unreadCount
            ];
        });

        // Urutkan berdasarkan waktu pesan terbaru
        $sortedUsers = $users->sortByDesc('last_time_obj')->values()->map(function($user) {
            unset($user['last_time_obj']);
            return $user;
        });

        return response()->json(['users' => $sortedUsers]);
    }

    public function getAdminMessages($username)
    {
        // Tandai pesan sebagai telah dibaca
        ConsultationMessage::where('username', $username)
            ->where('sender', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ConsultationMessage::where('username', $username)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'sender' => $msg->sender,
                    'type' => $msg->type,
                    'text' => $msg->message,
                    'time' => $msg->created_at->format('H:i')
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    public function sendAdminMessage(Request $request)
    {
        $message = ConsultationMessage::create([
            'username' => $request->user_id, // Isinya sebenernya username dari JS admin
            'sender' => 'admin',
            'type' => 'text',
            'message' => $request->message,
        ]);

        broadcast(new \App\Events\MessageSent($message))->toOthers();

        return response()->json(['success' => true, 'message' => $message]);
    }
}
