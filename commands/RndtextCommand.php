<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;


class RndtextCommand extends UserCommand
{
    protected $name = 'rndtext';
    protected $description = 'just rndtext';
    protected $usage = '/rndtext';
    protected $show_in_help = false;

    public function execute()
    {
        $isCron = FALSE;
        if (defined('STDIN')) {
            $isCron = TRUE;
        }

        $message = $this->getMessage();
        $chat_id = !$isCron ? $message->getChat()->getId() : -191031802;


        $qoutes = [];
        $qoutes[] = "";
        $qoutes[] = "Genau. Ganz Genau!";
        $qoutes[] = "So wird das nix!";

        $countQoutes = count($qoutes);
        $qouteRndIndex = rand(1, $countQoutes);

        if ($isCron) {
            $rand = rand(1, 100);
            if ($rand < 50) {
                $qoutes = [];
            }
        }
        $text = isset($qoutes[$qouteRndIndex]) ? $qoutes[$qouteRndIndex] : "";

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
