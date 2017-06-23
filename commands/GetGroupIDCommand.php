<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class GetGroupIDCommand extends UserCommand
{
    protected $name = 'getgroupid';
    protected $description = 'just get groupid';
    protected $usage = '/getgroupid';
    protected $show_in_help = false;

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $text = "Group-ID: " . $chat_id;

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
