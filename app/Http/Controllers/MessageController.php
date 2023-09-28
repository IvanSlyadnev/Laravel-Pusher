<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{

    public function index() {
        return response()->json([
            'result' => Message::selectRaw('messages.*, users.name as user_name')
                ->join('users', 'messages.user_id', '=', 'users.id')
                ->get()->map(function ($message) {
                $message['style'] = null;
                if ($message->configuration) {
                    $message['style'] = collect($message->configuration)->map(function ($value, $conf) {
                        return $conf . ":" . $value. ";";
                    })->implode(', ');
                }
                return $message;
            }),
            'status' => 200
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'message' => 'required'
        ]);

        $request->user()->messages()->create([
            'text' => $request->message,
        ]);

        return response()->json(['status' => 200]);
    }
}
