<?php

namespace app\controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ChatGPTService
{

    const HOST_MONITORING_MS_CHATGPT = 'http://monitoring-ms-chatgpt-api:8006';

    const BASE_PART = 'Тебе нельзя говорить, что ты бот / робот / не человек. Текст должен быть не более 300 символов в длину. Одним сообщением';

    public function ChatCompletion($messages): array
    {

        $processedMessages = [];
        foreach ($messages as $key => $message) {
            $messageFrom = preg_replace('/^.*\(/', '', $message['from_number_phone']);
            $messageFrom = str_replace([':', '@', ')'], '', $messageFrom);

            $processedMessages[] = [
                'role' => 'user',
                'content' => "{$messageFrom}: {$message['text_message']}"
            ];
        }

        $fuckedUpPromt = 'Тебе 40 лет, ты женщина, замужем и с детьми. Ты читаешь много статей и новостей, очень начитана. ' .
            'Продолжи диалог людей. Нужно отвечать максимально размыто. Стиль общения максимально простой и неформальный, ' .
            'можно иронизировать, допускать ошибки, проявлять эмоции можно но только когда это уместно. ' .
            'Старайся, чтобы манера сообщений была каждый раз разной. ' . self::BASE_PART;

        $paramsChatCompletion = [
            'messages' => $processedMessages,
            'systemMessage' => $fuckedUpPromt,
        ];

        try {
            $response = Http::withBody(json_encode($paramsChatCompletion), 'application/json')
                ->post(self::HOST_MONITORING_MS_CHATGPT . '/api/v1/chat/completion');
        } catch (\Exception $exception) {
            Log::error($exception);
            return [
                'status' => false,
                'error' => 'internal error',
            ];
        }

        return json_decode($response->body(), true);

    }

    public function generateText(string $text): array
    {
        $processedMessages = [];
        $processedMessages[] = [
            'role' => 'user',
            'content' => $text
        ];

        $promt = 'Перефразируй данный текст в 15ти вариантах, разделяя их только символом "#". ' . self::BASE_PART;

        $paramsChatCompletion = [
            'messages' => $processedMessages,
            'systemMessage' => $promt,
        ];

        try {
            $response = Http::withBody(json_encode($paramsChatCompletion), 'application/json')
                ->post(self::HOST_MONITORING_MS_CHATGPT . '/api/v1/chat/completion');
        } catch (\Exception $exception) {
            Log::error($exception);
            return [
                'status' => false,
                'error' => 'internal error',
            ];
        }

        return json_decode($response->body(), true);

    }

}
