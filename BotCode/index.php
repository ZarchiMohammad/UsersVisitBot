<?php
require_once("config.php");

$json = file_get_contents('php://input');
$tg = Telegram::getInstance($json);
$mt = Methods::getInstance();

$data = $tg->getText();
$chatId = $tg->getChatId();

if ($tg->isChannelPost() === false && $tg->isEditChannelPost() === false) {

    switch ($data) {
        case "/start":
            $mt->setStartMessage($chatId);
            break;

        case "/help":
            $message = "📌 Dear user, to use the statistical system, paste the following code snippet (script) in the Head tag according to the following example, and from the moment of placement onwards, this bot reports the statistics of your site or blog visits in an instant." . "\n";
            $message .= "\n" . "📌 If you want to receive the statistics of visits to several different sites or blogs at the same time, all you have to do is paste the code sent by the bot in the desired site or blog according to the example, the robot intelligently stores and sends the statistics of each site and blog separately for you." . "\n";
            $message .= "\n" . "📌 To remove the site or blog name from this system, just delete the embedded code from inside the Head tag." . "\n";
            $message .= "\n" . "📌 If you have any criticism or suggestions about this robot, you can contact me via the following ID:";
            $message .= "\n" . "- @ZarchiMohammad";
            $tg->sendMessage($chatId, $message);
            $message = _SCRIPT . $chatId . "\"></script>";
            $tg->sendCodeMessage($chatId, $message);
            $message = "Technical layout of the script:" . "\n \n";
            $message .= "<html>" . "\n";
            $message .= "<head>" . "\n";
            $message .= "<title>The name of your site or blog</title>" . "\n";
            $message .= "<- Script location ->" . "\n";
            $message .= "</head>" . "\n";
            $message .= "<body>" . "\n";
            $message .= "</body>" . "\n";
            $message .= "</html>" . "\n";
            $tg->sendCodeMessage($chatId, $message);
            break;

        case "/script":
            $message = _SCRIPT . $chatId . "\"></script>";
            $tg->sendCodeMessage($chatId, $message);
            break;
    }
}
