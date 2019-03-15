<?php

declare(strict_types=1);

namespace tim03we\advancedban\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use tim03we\advancedban\Main;

class BanListCommand extends Command {
	
	public function __construct(Main $plugin) {
		parent::__construct("banlist", "AdvancedBan", "/banlist");
		$this->setPermission("advanced.banlist.use");
		$this->plugin = $plugin;
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
		}
        $banlist = new Config($this->plugin->getDataFolder() . "banned-players.yml", Config::YAML);
        $settings = new Config($this->plugin->getDataFolder() . "settings.yml", Config::YAML);
        $deu = new Config($this->plugin->getDataFolder() . "messages/deu.yml", Config::YAML);
        $eng = new Config($this->plugin->getDataFolder() . "messages/eng.yml", Config::YAML);
        $list = array();
        foreach ($banlist->getAll(true) as $players) {
            array_push($list, $players);
        }
        if($settings->get("Language") === "deu") {
            $sender->sendMessage($deu->get("BanList"));
        } else {
            $sender->sendMessage($eng->get("BanList"));
        }
        $sender->sendMessage(implode(", ", $list));
        return false;
    }
}