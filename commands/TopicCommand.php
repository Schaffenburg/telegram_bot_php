<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\DB;
use PDO;

class TopicCommand extends UserCommand
{
    protected $name = 'topic';
    protected $description = 'show group topic';
    protected $usage = '/topic';

    public function execute()
    {
        $isCron = false;
        if (defined('STDIN')) {
            $isCron = true;
        }

        $isAdmin = false;
        $setTopic = false;
        $text = "";

        $message = $this->getMessage();
        $chat_id = !$isCron ? $message->getChat()->getId() : -191031802;

        $user_id = $message->getFrom()->getId();

        $pdo = DB::getPdo();

        if ($this->telegram->isAdmin($user_id)) {
            $isAdmin = true;
        }

        $content = $message->getText(true);

        if ($isAdmin && $content) {
            $setTopic = true;
        }

        if ($setTopic) {
            if ($this->telegram->isDbEnabled()) {
                $topic_text = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');
                $sql = "INSERT INTO `channel_topics` (`user_id`, `chat_id`, `topic_text`) VALUES (:user_id,:chat_id,:topic_text)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':chat_id', $chat_id);
                $stmt->bindParam(':topic_text', $topic_text);
                if ($stmt->execute()) {
                    $text = "New Topic set!";
                }

            } else {
                $text = "Error! Topic was not set, DB is not enabled!";
            }
        } else {
            $sql = "SELECT topic_text FROM channel_topics WHERE chat_id = :chat_id ORDER BY topic_id DESC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':chat_id', $chat_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $text = $result->topic_text;
        }

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];

        return Request::sendMessage($data);
    }
}
