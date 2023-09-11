<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class WebhookController extends Controller
{
    public function telegram() {
        $update = Telegram::commandsHandler(true);
        $chat_id = $update->getMessage()->getChat()->getId();
        Log::info($update);
    }
}
