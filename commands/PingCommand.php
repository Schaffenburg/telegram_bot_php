<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;


class PingCommand extends UserCommand
{
    protected $name = 'ping';
    protected $description = 'just ping';
    protected $usage = '/ping';

    public function execute()
    {
        $isCron = FALSE;
        if (defined('STDIN')) {
            $isCron = TRUE;
        }

        $message = $this->getMessage();
        $chat_id = !$isCron ? $message->getChat()->getId() : -191031802;

        $rnd = rand(1, 50);
        if ($rnd == 42) {
            $text = "I don't want to play with you!";
        } else {
            $text = 'pong';
        }

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
