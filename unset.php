<?php
$API_KEY = '';
$BOT_NAME = 'Schaffenbot';
$admin_id = 0; // userid von admin

//$GROUP_ID = -204065026; // bot test group
$GROUP_ID = -191031802; // schaffen cix

$BOT_URL = "https://api.telegram.org/bot" . $API_KEY . "/sendMessage";
$hook_url = ''; //url zur hook.php

$mysql_credentials = [
    'host' => 'localhost',
    'user' => '',
    'password' => '',
    'database' => '',
];


require __DIR__ . '/../vendor/autoload.php';
$commands_path = __DIR__ . '/commands/';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    // Delete webhook
    $result = $telegram->deleteWebhook();

    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e;
}
