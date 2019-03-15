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

class BanIDListCommand extends Command {
	
	public function __construct(Main $plugin) {
		parent::__construct("banids", "AdvancedBan", "/banids");
		$this->setPermission("advanced.banids.use");
		$this->plugin = $plugin;
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
        }
        $settings = new Config($this->plugin->getDataFolder() . "settings.yml", Config::YAML);
        $deu = new Config($this->plugin->getDataFolder() . "messages/deu.yml", Config::YAML);
        $eng = new Config($this->plugin->getDataFolder() . "messages/eng.yml", Config::YAML);
        if($settings->get("Language") === "deu") {
            $sender->sendMessage($deu->get("ID-List"));
        } else {
            $sender->sendMessage($eng->get("ID-List"));
        }
        return false;
    }
}