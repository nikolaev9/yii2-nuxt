<?php

namespace app\controllers;

use App\Models\Theme;
use App\Services\TelegramService;
use Illuminate\Database\Eloquent\Collection;
use function App\Messengers\config;

class ProcessingHelper
{

    /**
     * Проверяет сообщение на принадлежность к теме и отправляет увед если принадлежит теме
     * Если по одной теме уже была отправка в канал, то по второй теме в тот же канал оно не отправится
     *
     * @param $preparedText
     * @param $postText
     * @param $messengerName
     *
     * @return void
     */
    public static function checkMessageForTheme($preparedText, $postText, $messengerName)
    {
        $themes = Theme::with('bots', 'messengers', 'tags', 'negative_keyword')
            ->where(['archive' => 0])
            ->whereHas('messengers', function ($q) use ($messengerName) {
                $q->where('name', $messengerName);
            })->get();
        $matchedThemes = self::findMatchedThemes($themes, $postText);

        $sentChatsIds = [];

        foreach ($matchedThemes as $theme) {
            $token = null;
            if (is_null($theme->bots)) {
                $token = config('telegram.bots.mybot.token');
                $telegramChannelPrivateId = config('app.channel_id');
            } else {
                $token = $theme->bots->bot_token;
                $telegramChannelPrivateId = $theme->bots->telegram_channel_private_id;
            }

            if ($token != null && !in_array($telegramChannelPrivateId, $sentChatsIds)) {
                $text = '<b>Внимание, новое сообщение по теме ' . '"' . $theme->name . '" </b>' . " \n" .
                    $preparedText . " \n" .
                    'Текст сообщения: ' . $postText;

                (new TelegramService())->sendMessage($text, $token, $telegramChannelPrivateId);
                $sentChatsIds[] = $telegramChannelPrivateId;
            }
        }
    }

    /**
     * Проверяет, относится ли текст к темам.
     *
     * @param Collection $themes Темы
     * @param string $postText Текст
     *
     * @return array - Массив тем, к которым подошло
     */
    public static function findMatchedThemes(Collection $themes, string $postText): array
    {
        $matched = [];

        foreach ($themes as $theme) {
            foreach ($theme->negative_keyword as $keyword) {
                if (self::checkTextExist($keyword->pivot->full_text_search, $postText, $keyword->keyword)) {
                    continue 2;
                }
            }

            foreach ($theme->tags as $tag) {
                if (self::checkTextExist($tag->pivot->full_text_search, $postText, $tag->text)) {
                    $matched[] = $theme;
                    break;
                }
            }
        }

        return $matched;
    }

    /**
     * Проверяет наличие ключевого слова в тексте
     *
     * @param bool $fullTextSearch - Флаг полнотекстовой проверки
     * @param string $text - Текст в котором надо искать
     * @param string $searchable - То, что нужно найти
     *
     * @return bool - Возвращает true если найдено и false если не найдено
     */
    private static function checkTextExist(bool $fullTextSearch, string $text, string $searchable): bool
    {
        if (!$fullTextSearch) {
            if (stristr(mb_strtolower($text), mb_strtolower($searchable)) === false) {
                return false;
            }
        } else {
            if (!preg_match(
                '/\b' . str_replace('/', '\/', preg_quote(mb_strtolower($searchable))) . '\b/iu',
                mb_strtolower($text)
            )) {
                return false;
            }
        }

        return true;
    }

}
