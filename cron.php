<?php
require __DIR__ . '/../vendor/autoload.php';

$API_KEY = '';
$BOT_NAME = 'Schaffenbot';
$admin_id = ; //userid von admin

$mysql_credentials = [
    'host' => 'localhost',
    'user' => '',
    'password' => '',
    'database' => '',
];

$commands_path = __DIR__ . '/commands/';

$commands[] = '/spacestatus';


try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($API_KEY, $BOT_NAME);

    // Enable MySQL
    $telegram->enableMySql($mysql_credentials);

    // Add an additional commands path
    $telegram->addCommandsPath($commands_path);

    // Run user selected commands
    $telegram->runCommands($commands);
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    //echo $e;
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Silence is golden!
    // Uncomment this to catch log initilization errors
    //echo $e;
}
