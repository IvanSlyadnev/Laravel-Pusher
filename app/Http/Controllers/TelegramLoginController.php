<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Notifications\CodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TelegramLoginController extends Controller
{
    public function create() {
        return view('auth.telegram');
    }

    public function login(Request $request) {

        if ($user = User::where('phone', $request->phone)->first()) {
            $user->update([
                'code' => mt_rand(100000, 999999)
            ]);
            $user->notify(new CodeNotification());
            return redirect()->route('telegram.code', ['phone' => $request->phone]);
        }
        return redirect()->route('login')->withErrors('Пользователь с таким номером телефона не найден');
    }

    public function telegramAuth(Request $request, $phone) {
        if ($request->code && $phone) {
            if ($user = User::where('phone', $phone)->first()){
                if ($user->code == $request->code) {
                    Auth::login($user);
                    return redirect()->route('dashboard');
                } else {
                    return redirect()->back()->withErrors('Не верный код');
                }
            } else {
                return redirect()->back()->withErrors('Пользователь с таким номером телефона не найден');
            }
        }
    }

    public function code(Request $request, $phone) {
        return view('auth.telegramCode', [
            'phone' => $phone
        ]);
    }
}
