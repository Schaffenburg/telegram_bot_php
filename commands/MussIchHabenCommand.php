<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;


class MussIchHabenCommand extends UserCommand
{
    protected $name = 'mussichhaben';
    protected $description = '';
    protected $usage = '/mussichhaben';
    protected $show_in_help = false;

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $text = 'Nicht!';

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
