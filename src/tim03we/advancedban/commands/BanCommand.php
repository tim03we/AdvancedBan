<?php

declare(strict_types=1);

namespace tim03we\advancedban\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as c;
use pocketmine\utils\Config;
use pocketmine\Server;
use tim03we\advancedban\Main;

class BanCommand extends Command {
    
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

	public function __construct(Main $plugin) {
		parent::__construct("ban", "AdvancedBan", "/ban <player> <id>");
		$this->setPermission("advanced.ban.use");
        $this->plugin = $plugin;
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
        if(isset($args[0])) {
            if(isset($args[1])) {
                if(array_key_exists($args[1], $this->bansGerman)){
                    $settings = new Config($this->plugin->getDataFolder() . "settings.yml", Config::YAML);
                    $banlist = new Config($this->plugin->getDataFolder() . "banned-players.yml", Config::YAML);
                    $deu = new Config($this->plugin->getDataFolder() . "messages/deu.yml", Config::YAML);
                    $eng = new Config($this->plugin->getDataFolder() . "messages/eng.yml", Config::YAML);
                    $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                    $idList = $this->bansGerman[$args[1]];
                    $duration = explode(':', $idList['Duration']);
                    $date = new \DateTime('now');
                    if ($duration[0] == 'T') {
                        $date->add(new \DateInterval('PT'.$duration[1]*"1".$duration[2]));
                    } else {
                        $date->add(new \DateInterval('P'.$duration[1]*"1".$duration[2]));
                    }
                    $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                    $format = $date->format('Y-m-d H:i:s');
                    $by = $sender->getName();
                    $id = $args[1];
                    $banlist->set(strtolower($args[0]), 0);
                    if($target == null) {
                        $banlist->set(strtolower($args[0]), $id . ", " . $by . ", " . $format);
                        $sender->sendMessage("The player " . strtolower($args[0]) . " could not be found, but was banned anyway.");
                    } else {
                        $banlist->set(strtolower($sender2->getName()), $id . ", " . $by . ", " . $format);
                        if($settings->get("Language") === "deu") {
                            $msg = $deu->get("Success");
                            $msg = str_replace("{reason}", $idList['Reason'], $msg);
                            $msg = str_replace("{banned-player}", strtolower($sender2->getName()), $msg);
                            $sender->sendMessage($msg);
                            $target->kick($deu->get("Kick-Message"), false);
                        } else if($settings->get("Language") === "eng") {
                            $msg = $eng->get("Success");
                            $msg = str_replace("{reason}", $idList['Reason'], $msg);
                            $msg = str_replace("{banned-player}", strtolower($sender2->getName()), $msg);
                            $sender->sendMessage($msg);
                            $target->kick($eng->get("Kick-Message"), false);
                        }
                    }
                    $settings->save();
                    $banlist->save();
                    $banlist->reload();
                } else {
                    $sender->sendMessage($this->getUsage());
                }
            } else {
                $sender->sendMessage($this->getUsage());
            }
        } else {
            $sender->sendMessage($this->getUsage());
        }
        return false;
    }

    public function convert(string $string, $reason, $bannedPlayer): string{
        $reason = str_replace("{reason}", $reason, $string);
        $bannedPlayer = str_replace("{banned-player}", $bannedPlayer, $string);
        return $string;
	}
}