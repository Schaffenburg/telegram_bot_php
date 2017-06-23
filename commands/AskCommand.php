<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class AskCommand extends UserCommand
{
    protected $name = 'ask';
    protected $description = '';
    protected $usage = '/ask';

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $data = [
            'chat_id' => $chat_id,
            'text' => "Don't ask to ask, just ask!",
        ];

        return Request::sendMessage($data);
    }
}
