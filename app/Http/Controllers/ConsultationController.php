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
        ConsultationMessage::create([
            'username' => Auth::user()->username,
            'sender' => $request->sender ?? 'user',
            'type' => $request->type ?? 'text',
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }

    // --- SISI ADMIN ---
    public function getChatUsers()
    {
        // Ambil semua username unik yang pernah chat
        $usernames = ConsultationMessage::select('username')->distinct()->pluck('username');

        // Ambil info user tersebut
        $users = \App\Models\User::whereIn('username', $usernames)->get()->map(function($user) {
            $lastMsg = ConsultationMessage::where('username', $user->username)->latest()->first();
            return [
                'id' => $user->username, // ID di sini kita isi string username
                'name' => $user->username,
                'last_message' => $lastMsg ? \Illuminate\Support\Str::limit($lastMsg->message, 30) : '',
                'time' => $lastMsg ? $lastMsg->created_at->format('H:i') : ''
            ];
        });

        return response()->json(['users' => $users]);
    }

    public function getAdminMessages($username)
    {
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
        ConsultationMessage::create([
            'username' => $request->user_id, // Isinya sebenernya username dari JS admin
            'sender' => 'admin',
            'type' => 'text',
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }
}
