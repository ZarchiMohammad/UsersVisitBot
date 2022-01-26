<?php
require_once("system/Telegram.php");
require_once("system/Database.php");
require_once("system/Methods.php");

const _TOKEN = "--BotToken--";
const _BOT_VERSION = 1;
const _BOT_BIRTHDAY = 1617814587;
const _REPORT_CHANNEL = "--ChatId--";
const _ZARCHI_CHANNEL = "--ChatId--";
const _SCRIPT = "<script type=\"text/javascript\" src=\"https://codeedit.ir/bots/Telegram/UsersVisitBot/who.php?user=";

// config database.
global $config;
$config['host'] = "localhost";
$config['user'] = "--Username--";
$config['pass'] = "--Password--";
$config['name'] = "--DatabaseName--";
