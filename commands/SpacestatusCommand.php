<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\DB;
use Longman\TelegramBot\Request;
use PDO;

class SpacestatusCommand extends UserCommand
{

    protected $name = 'spacestatus';
    protected $description = 'get spacestatus';
    protected $usage = '/spacestatus';

    public function execute()
    {
        $text = FALSE;
        $status_message = FALSE;
        $status_short_after = FALSE;
        $status_short_before = FALSE;
        $status_full_after = FALSE;
        $status_full_before = FALSE;
        $statusNotWorking = FALSE;
        $statusChanged = FALSE;

        $status = trim(file_get_contents('http://status.schaffenburg.org/'));

        $pdo = DB::getPdo();

        $isCron = FALSE;
        if (defined('STDIN')) {
            $isCron = TRUE;
        }

        $message = $this->getMessage();
        $chat_id = !$isCron ? $message->getChat()->getId() : -191031802;

        $sql = "SELECT status_short,status_full FROM spacestatus ORDER BY status_id DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $status_short_before = $result->status_short;
        $status_full_before = $result->status_full;

        switch ($status) {
            case "offen":
                $status_message = "Space open";
                $status_content = 1;
                break;
            case "verriegelt":
                $status_message = "Space closed";
                $status_content = 0;
                break;
            default:
                $status_message = "Spacestatus not working";
                $status_content = 2;
        }

        if ($isCron) {
            $status_short_after = $status_content;
            $status_full_after = $status_content;

            if (($status_content == 0 || $status_content == 1) && $status_short_after != $status_short_before) {
                $statusChanged = true;
            }

            if ($status_content != 0 && $status_content != 1) {
                $status_short_after = $status_short_before;
            }

            if ($status_full_before == $status_full_after && $status_full_after != 0 && $status_full_after != 1) {
                $sql = "SELECT option_value FROM options WHERE option_key = 'spacestatus_not_working_send' LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_OBJ);
                $setNotWorking = $result->option_value;

                if ($setNotWorking == 0) {
                    $statusNotWorking = true;
                    $sql = "UPDATE options SET option_value = 1 WHERE option_key = 'spacestatus_not_working_send'";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                }
            }

            if ($status_content == 0 || $status_content == 1) {
                $sql = "UPDATE options SET option_value = 0 WHERE option_key = 'spacestatus_not_working_send'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }

            $sql = "INSERT INTO spacestatus (status_short,status_full) VALUES (:status_short,:status_full)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':status_short', $status_short_after);
            $stmt->bindParam(':status_full', $status_full_after);
            $stmt->execute();
        }

        $text = $status_message;

        if (!$isCron) {
	

            $lastStatus = !$status_content;
            $sql = "SELECT status_timestamp FROM spacestatus WHERE status_short = :laststatus ORDER BY status_id DESC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':laststatus', $lastStatus);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $statusSinceDateTimestamp = strtotime($result->status_timestamp);

            $statusSinceDateDay = date("w", $statusSinceDateTimestamp);

            //$days = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
	        $days = ["Sunday","Monday", "Tuesday","Wednesday","Thursday","Friday","Saturday" ];
            date_default_timezone_set("Europe/Berlin");

            $statusSinceDateDay = $days[$statusSinceDateDay];
            $statusSinceDate = date("d.m.Y H:i", $statusSinceDateTimestamp);


            $text = $status_message . " - since " .$statusSinceDateDay . " " . $statusSinceDate;
//            $text = $status_message;

        }

        if (($isCron && $statusChanged) || ($statusNotWorking) || !$isCron) {
            $data = [
                'chat_id' => $chat_id,
                'text' => $text,
            ];
        }

        if (!isset($data)) {
            return "";
        }

        return Request::sendMessage($data);
    }
}
