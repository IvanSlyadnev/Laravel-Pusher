<?php

namespace App\Http\Middleware\Telegram;

use App\Models\Client;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Auth
{
    public function handle(Request $request, Closure $next): Response
    {
        /*if ($data = $this->getTelegramChatId($request)) {
            $id = $data['id'];
            if ($user = User::where('telegram_chat_id',$id)->first()) {
                $request->merge([
                    'user' => $user
                ]);
                return $next($request);
            }
        }*/
        return $next($request);
    }

    protected function getTelegramChatId(Request $request) {
        if (isset($request['callback_query'])) {
            $callback = $request['callback_query'];
        }

        return isset($callback) ? [
            'id' => $callback['message']['chat']['id'],
            'name' => $callback['message']['chat']['first_name']
        ] : ($request['message']['from']['id'] ? [
            'id' => $request['message']['from']['id'],
            'name' => $request['message']['from']['first_name']
        ] : null);
    }
}
