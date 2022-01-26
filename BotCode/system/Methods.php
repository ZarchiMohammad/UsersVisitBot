<?php


class Methods
{
    private static $mt;
    private static $db;
    private static $tg;

    public static function getInstance()
    {
        if (self::$mt == null) {
            self::$mt = new Methods();
        }
        return self::$mt;
    }

    private function __construct()
    {
        self::$db = Database::getInstance();
        self::$tg = Telegram::getInstance();
    }

    public function sendUserEntrance($chatId, $function)
    {
        if (strlen($chatId) > 4) {
            $message = "User: <code>" . $chatId . "</code>\n";
            $message .= "Function: <code>" . $function . "</code>" . "\n";
            $message .= "TimeStamp: <code>" . time() . "</code>\n";
            self::$tg->sendMessage(_REPORT_CHANNEL, $message);
        }
    }

    public function setStartMessage($chatId)
    {
        self::$tg->setChatAction($chatId);
        $this->sendUserEntrance($chatId, __FUNCTION__);
        $message = "Hi dear user!" . "\n";
        $message .= "Welcome to User Visit Monitoring Bot." . "\n";
        $message .= "Enter the /help command for how to use the bot.\n";
        self::$db->insertUserData($chatId, time());
        self::$tg->sendMessage($chatId, $message);
    }

    public function baseManager($base)
    {
        $result = array();
        $replace = preg_split('/^(http(|s):\/\/([^\/]+))/', $base, -1, PREG_SPLIT_NO_EMPTY)[0];
        $base = str_replace($replace, "", $base);
        $base = str_replace("www.", "", $base);
        $base = preg_replace('/^(http(|s):\/\/([^\/]+))/', "$3", $base); //
        if (self::$db->searchBlog($base)) {
            $data = self::$db->getBlogData($base);
            if ($data['_dYearMonth'] == date('Y-m')) {
                // اگر ماه و سال برابر بود
                if ($data['_dDay'] == date('d')) {
                    // اگر روز برابر بود
                    $result['_day'] = $data['_day'] + 1;
                    $result['_month'] = $data['_month'] + 1;
                    self::$db->updateBlog("MonthDay", $data['_id'], null, null, $result['_day'], $result['_month']);
                } else {
                    // اگر روز برابر نبود
                    $result['_day'] = 1;
                    $result['_month'] = $data['_month'] + 1;
                    self::$db->updateBlog("Month", $data['_id'], null, "" . date("day"), null, $result['_month']);
                }
            } else {
                // اگر ماه و سال برابر نبود
                self::$db->updateBlog("YearMonth", $data['_id'], date('Y-m'), date('d'), null, null);
                $result['_day'] = 1;
                $result['_month'] = 1;
            }
        } else {
            // اگر پایگاه در دیتابیس وجود نداشت
            self::$db->insertBlog($base, time(), date('Y-m'), date('d'));
            $result['_day'] = 1;
            $result['_month'] = 1;
        }
        return $result;
    }
}