<?php

namespace tim03we\advancedban\Events;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\entity\Entity;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as c;
use tim03we\advancedban\Main;
use DateTime;

class EventListener implements Listener {

    public $bansGerman = array(
        1 => ['Reason' => 'Hacking', 'Duration' => '0:30:D'],
        2 => ['Reason' => 'Beleidigung', 'Duration' => '0:1:D'],
        3 => ['Reason' => 'Respektloses Verhalten', 'Duration' => 'T:2:H'],
        4 => ['Reason' => 'Provokantes Verhalten', 'Duration' => 'T:1:H'],
        5 => ['Reason' => 'Spamming', 'Duration' => 'T:1:H'],
        6 => ['Reason' => 'Werbung', 'Duration' => '0:3:D'],
        7 => ['Reason' => 'Report Missbrauch', 'Duration' => 'T:1:H'],
        8 => ['Reason' => 'Wortwahl / Drohung', 'Duration' => '0:14:D'],
        9 => ['Reason' => 'Teaming', 'Duration' => '0:3:D'],
        10 => ['Reason' => 'Bugusing', 'Duration' => '0:1:D'],
        99 => ['Reason' => 'Ban von einem Admin', 'Duration' => '0:12:M']
    );

    public $bansEnglish = array(
        1 => ['Reason' => 'Hacking', 'Duration' => '0:30:D'],
        2 => ['Reason' => 'Insult', 'Duration' => '0:1:D'],
        3 => ['Reason' => 'Disrespectful behavior', 'Duration' => 'T:2:H'],
        4 => ['Reason' => 'Provocative behavior', 'Duration' => 'T:1:H'],
        5 => ['Reason' => 'Spamming', 'Duration' => 'T:1:H'],
        6 => ['Reason' => 'Advertising', 'Duration' => '0:3:D'],
        7 => ['Reason' => 'Report abuse', 'Duration' => '0:3:D'],
        8 => ['Reason' => 'Word choice / threat', 'Duration' => '0:14:D'],
        9 => ['Reason' => 'Teaming', 'Duration' => '0:3:D'],
        10 => ['Reason' => 'Bugusing', 'Duration' => '0:1:D'],
        99 => ['Reason' => 'Ban from an admin', 'Duration' => '0:12:M']
    );

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
	}
    
    public function onLogin(PlayerPreLoginEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        $banlist = new Config($this->plugin->getDataFolder() . "banned-players.yml", Config::YAML);
        $settings = new Config($this->plugin->getDataFolder() . "settings.yml", Config::YAML);
        if(!$banlist->get(strtolower($name))) {
            return true;
        } else {
            $check = explode(', ', $banlist->get(strtolower($name)));
            $id = $check[0];
            $bannedBy = $check[1];
            $bantime = new DateTime($check[2]);
            if($settings->get("Language") === "deu") {
                $banid = $this->bansGerman[$id];
            } else {
                $banid = $this->bansEnglish[$id];
            }
            if (new DateTime("now") < $bantime) {
                $time = new DateTime("now");
                $tFormat = $time->format('Y:m:d:H:i:s');
                $zone = explode(":", $tFormat);
                $bantime->sub(new \DateInterval("P" . $zone[0] . "Y" . $zone[1] . "M" . $zone[2] . "DT" . $zone[3] . "H" . $zone[4] . "M" . $zone[5] . "S"));
                $bFormat = $bantime->format('m:d:H:i:s');
                $duration = explode(":", $bFormat);
                $month = $duration[0];
                $day = $duration[1];
                $hour = $duration[2];
                $minute = $duration[3];
                $second = $duration[4];
                if($check[0] === "1") {
                    if($settings->get("Language") === "deu") {
                        $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $day . " Days, " . $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "2") {
                    if($settings->get("Language") === "deu") {
                        $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $day . " Days, " . $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "3") {
                    if($settings->get("Language") === "deu") {
                        $message = $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "4") {
                    if($settings->get("Language") === "deu") {
                        $message = $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "5") {
                    if($settings->get("Language") === "deu") {
                        $message = $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "6") {
                    if($settings->get("Language") === "deu") {
                        $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $day . " Days, " . $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "7") {
                    if($settings->get("Language") === "deu") {
                        $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $day . " Days, " . $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "8") {
                    if($settings->get("Language") === "deu") {
                        $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $day . " Days, " . $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "9") {
                    if($settings->get("Language") === "deu") {
                        $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $day . " Days, " . $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "10") {
                    if($settings->get("Language") === "deu") {
                        $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $day . " Days, " . $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                } else if($check[0] === "99") {
                    if($settings->get("Language") === "deu") {
                        $message = $month . " Monate, " . $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    } else {
                        $message = $month . " Months, " . $day . " Days, " . $hour . " Hours, " . $minute . " Minutes, " . $second . " Seconds.";
                    }
                    if($settings->get("Language") === "deu") {
                        $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                    } else {
                        $player->kick("§4You was banned from our Network!\n§cReason:§7 " . $banid['Reason'] . " §cBy:§7 " . $bannedBy . "\n§cDuration:§7 " . $message, false);
                    }
                }
            }
        }
    }
}