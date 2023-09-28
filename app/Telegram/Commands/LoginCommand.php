<?php

namespace App\Telegram\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Actions;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Audio;

class LoginCommand extends \Telegram\Bot\Commands\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $name = 'login';

    protected string $description = 'Auth Command to get you started';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->replyWithChatAction(['action' => Actions::TYPING]);
        if ($this->getUpdate()->isType('message') && $this->getUpdate()->getMessage()->getContact()) {
            $phone = clear_phone($this->getUpdate()->getMessage()->getContact()->getPhoneNumber());
            if ($user = \App\Models\User::where('phone', $phone)->first()) {
                $user->update([
                    'telegram_chat_id' => $this->getUpdate()->getMessage()->getFrom()->getId()
                ]);
                return $this->replyWithMessage([
                    'text' => 'Вы авторизованы',
                    'reply_markup' => Keyboard::remove()
                ]);
            } else {
                return $this->replyWithMessage([
                    'text' => 'Пользователь с таким номером телефона не найден',
                    'reply_markup' =>
                        Keyboard::make()->inline()->row([
                            [
                                'text' => 'Падлюка',
                                'url' => route('register'),
                            ],
                            [
                                'text' => 'Зарегестрировать меня без пароля',
                                'callback_data' => 'register|'.$phone
                            ]
                        ])
                ]);
            }
        }
        if ($this->getUpdate()->isType('callback_query')) {
            Telegram::answerCallbackQuery(['callback_query_id' => $this->getUpdate()->getCallbackQuery()->getId()]);
            $phone = explode('|', $this->getUpdate()->getCallbackQuery()->getData());
            User::create([
                'phone' => $phone[1],
                'name' => $this->getUpdate()->getCallbackQuery()->getFrom()->getFirstName(),
                'telegram_chat_id' => $this->getUpdate()->getCallbackQuery()->getFrom()->getId()
            ]);
            Telegram::deleteMessage([
                'message_id' => $this->getUpdate()->getCallbackQuery()->getMessage()->getMessageId(),
                'chat_id' => $this->getUpdate()->getCallbackQuery()->getFrom()->getId(),
            ]);
            Telegram::sendMessage([
                'chat_id' => $this->getUpdate()->getCallbackQuery()->getFrom()->getId(),
                'text' => 'Вы авторизованы',
                'reply_markup' => Keyboard::remove()
            ]);
        } else {
            $this->replyWithMessage([
                'chat_id' => $this->getUpdate()->getChat()->getId(),
                'text' => 'Необходимо зарегаться',
                'reply_markup' => Keyboard::make()->setResizeKeyboard(true)
                    ->row([
                            Keyboard::button([
                                'text' => 'Войти',
                                'request_contact' => true
                            ])]
                    )
            ]);
        }
    }
}
