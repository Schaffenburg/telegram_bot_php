<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class WhatistheanswerforCommand extends UserCommand
{

    protected $name = 'whatistheanswerfor';
    protected $description = '';
    protected $usage = '/whatistheanswerfor';

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $text = 'The Answer is 42!';

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
