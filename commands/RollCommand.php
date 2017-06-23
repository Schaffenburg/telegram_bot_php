<?php
namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

class RollCommand extends UserCommand
{

    protected $name = 'roll';
    protected $description = 'just roll the dice';
    protected $usage = '/roll number';

    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $number = intval(trim($message->getText(true)));

        $result = rand(1, $number);

        $text = $result . " it is!";

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
