<?php
require_once("config.php");

$json = file_get_contents('php://input');
$tg = Telegram::getInstance($json);
$mt = Methods::getInstance();

$chatId = $_REQUEST['user'];
$base = rtrim($_SERVER['HTTP_REFERER'], "/");
if (strlen($base) > 0) {
    $data = $mt->baseManager($base);
} else {
    $data['_day'] = 1;
    $data['_month'] = 1;
    $base = "Your Domain Address.";
}

$message = "📌 New visit!" . "\n";
$message .= "Time : " . date('H:i:s') . " UTC" . "\n";
$message .= "Date : <code>" . date('Y-m-d') . "</code>" . "\n";
$message .= "IP Address : <code>" . $_SERVER['REMOTE_ADDR'] . "</code>\n";
$message .= "Daily visit(s) : " . number_format($data['_day']) . "\n";
$message .= "Monthly visit(s) : " . number_format($data['_month']) . "\n";
$message .= "Browser : " . $_SERVER['HTTP_USER_AGENT'] . "\n";
$message .= "Address : " . $base . "\n";
$tg->sendMessage($chatId, $message, true);
