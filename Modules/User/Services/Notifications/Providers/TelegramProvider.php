<?php

namespace Modules\User\Services\Notifications\Providers;

use Illuminate\Support\Facades\Http;

use Modules\User\Services\Notifications\Providers\Contracts\Provider;
use Throwable;

class TelegramProvider implements Provider
{
    public string $text;
    private string $chatID;
    private string $token;
    private string $proxy;


    public function __construct(string $text)
    {
        $this->text = $text;
        $this->chatID = config('front.notification.telegram.chat_id');
        $this->token = config('front.notification.telegram.bot_token');
        //$this->proxy = config('front.notification.telegram.proxy');
    }

    /**
     * @throws Throwable
     */
    public function send()
    {

        \DB::beginTransaction();
        try {

            $response = Http::get('https://api.rinofy.ir/telegram/send-message', [
                    'chat_id' => $this->chatID,
                    'token' => $this->token,
                    'text' => $this->text,
                ])->json();

            \Log::info('Telegram Notification: ' . $response['ok']);

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            \Log::warning($exception->getMessage());
            return null;
        }

    }
}
