<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;


class HalloCommand extends UserCommand
{
    protected $name = 'hallo';
    protected $description = '';
    protected $usage = '/hallo';

    public function execute()
    {

        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $member = $message->getFrom();

        $text = "";

        if ($member) {
            $text = 'Hallo ' . $member->tryMention() . '!';
        }


        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
