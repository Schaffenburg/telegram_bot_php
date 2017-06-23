<?php
$API_KEY = '';
$BOT_NAME = 'Schaffenbot';
$admin_id = ; //userid von admin

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

    // add custom commands
    $commands_folder = __DIR__ . '/commands/';
    $telegram->addCommandsPath($commands_folder);

    // Enable MySQL
    $telegram->enableMySql($mysql_credentials);

    $telegram->enableAdmin($admin_id);

    Longman\TelegramBot\TelegramLog::initUpdateLog($BOT_NAME . '_update.log');
    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e;
}
