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

class UnBanCommand extends Command {
	
	public function __construct(Main $plugin) {
		parent::__construct("unban", "AdvancedBan", "/unban <player>", ["pardon"]);
		$this->setPermission("advanced.unban.use");
		$this->plugin = $plugin;
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if(!$this->testPermission($sender)) {
			return false;
        }
        $settings = new Config($this->plugin->getDataFolder() . "settings.yml", Config::YAML);
        $banlist = new Config($this->plugin->getDataFolder() . "banned-players.yml", Config::YAML);
        $deu = new Config($this->plugin->getDataFolder() . "messages/deu.yml", Config::YAML);
        $eng = new Config($this->plugin->getDataFolder() . "messages/eng.yml", Config::YAML);
        if(empty($args[0])) {
            $sender->sendMessage($this->getUsage());
            return true;
        }
        $name = $args[0];
        if(!$banlist->get(strtolower($name))) {
            if($settings->get("Language") === "deu") {
                $sender->sendMessage($deu->get("UnbanError-Message"));
            } else {
                $sender->sendMessage($eng->get("UnbanError-Message"));
            }
            return true;
        }
        $banlist->remove(strtolower($name));
        $banlist->save();
        $banlist->reload();
        if($settings->get("Language") === "deu") {
            $sender->sendMessage($this->convert($deu->get("Unban-Message"), $name));
        } else {
            $sender->sendMessage($this->convert($eng->get("Unban-Message"), $name));
        }
        return false;
    }

    public function convert(string $string, $name): string{
        $string = str_replace("{player}", $name, $string);
        return $string;
	}
}