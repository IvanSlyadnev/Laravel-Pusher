<?php

namespace App\Http\Controllers;

use App\Events\MessageCreated;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;

class WebhookController extends Controller
{
    public function telegram(Request $request) {
        $update = Telegram::commandsHandler(true);
        $chat_id = $update->getMessage()->getChat()->getId();

        $text = $update->getMessage()->getText();
        $user = User::where('telegram_chat_id', $chat_id)->first();

        if (!$user) {
            Telegram::triggerCommand('login', $update);
            return;
        } else {
            if (str_contains($text, 'color:')) {
                Log::info('color');
                $style = str_replace('color:', '', strstr($text, 'color:'));
                $text = strstr($text, "color:", true);
            }
            $user->messages()->create([
                'text' =>  $text,
                'configuration' => ['color' => $style ?? null]
            ]);
        }
    }
}
