<?php


class Telegram
{
    private static $tg;
    private static $jsonData;

    public static function getInstance($json = null)
    {
        if (self::$tg == null) {
            self::$tg = new Telegram($json);
        }
        return self::$tg;
    }

    private function __construct($json = null)
    {
        if ($json != null) {
            self::$jsonData = json_decode($json);
        }
    }

    public function getChatId()
    {
        return self::$jsonData->message->chat->id;
    }

    public function getUserName()
    {
        if (isset(self::$jsonData->message->from->username))
            return "@" . self::$jsonData->message->from->username;
        else
            return null;
    }

    public function getDate()
    {
        return self::$jsonData->message->date;
    }

    public function getMessageType()
    {
        if (isset(self::$jsonData->message->audio))
            return "audio";
        elseif (isset(self::$jsonData->message->document))
            return "document";
        elseif (isset(self::$jsonData->message->photo))
            return "photo";
        elseif (isset(self::$jsonData->message->voice))
            return "voice";
        elseif (isset(self::$jsonData->message->text))
            return "text";
    }

    /*
     * Telegram Methods For Send Message.
     */

    public function sendMessage($chatId, $message, $link = false)
    {
        $message = urlencode($message);
        $url = "https://api.telegram.org/bot" . _TOKEN;
        $url .= "/sendMessage?chat_id=" . $chatId;
        $url .= "&text=" . $message;
        $url .= "&parse_mode=html";
        if ($link)
            $url .= "&disable_web_page_preview=true";
        file_get_contents($url);
    }

    public function sendCodeMessage($chatId, $message)
    {
        $message = urlencode($message);
        $url = "https://api.telegram.org/bot" . _TOKEN;
        $url .= "/sendMessage?chat_id=" . $chatId;
        $url .= "&text=" . $message;
        $url .= "&parse_mode=markdown";
        file_get_contents($url);
    }

    public function editMessage($chatId, $messageId, $text)
    {
        $text = urlencode($text);
        $url = "https://api.telegram.org/bot" . _TOKEN;
        $url .= "/editMessageText?chat_id=" . $chatId;
        $url .= "&message_id=" . $messageId;
        $url .= "&text=" . $text;
        $url .= "&parse_mode=html";
        file_get_contents($url);
    }

    public function setChatAction($chatId, $action = "typing")
    {
        /* typing for text messages
         * upload_photo for photos
         * upload_video for videos
         * record_video for video recording
         * upload_audio for audio files
         * record_audio for audio file recording
         * upload_document for general files
         * find_location for location data
         * upload_video_note for video notes
         * record_video_note for video note recording */

        $url = "https://api.telegram.org/bot" . _TOKEN;
        $url .= "/sendChatAction?chat_id=" . $chatId;
        $url .= "&action=" . $action;
        return file_get_contents($url);
    }

    public function isChannelPost()
    {
        $result = false;
        if (isset(self::$jsonData->channel_post))
            $result = true;
        return $result;
    }

    public function isEditChannelPost()
    {
        $result = false;
        if (isset(self::$jsonData->edited_channel_post))
            $result = self::$jsonData->edited_channel_post;
        return $result;
    }
}
