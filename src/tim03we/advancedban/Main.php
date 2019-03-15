<?php

namespace tim03we\advancedban;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as c;
use pocketmine\plugin\PluginBase;
use DateTime;
use tim03we\advancedban\Events\EventListener;
use tim03we\advancedban\commands\BanCommand;
use tim03we\advancedban\commands\BanListCommand;
use tim03we\advancedban\commands\UnBanCommand;
use tim03we\advancedban\commands\BanIDListCommand;

class Main extends PluginBase implements Listener {

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->saveResource("settings.yml");
        $this->saveResource("banned-players.yml");
        $this->saveResource("messages/deu.yml");
        $this->saveResource("messages/eng.yml");
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("ban"));
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("pardon"));
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("banlist"));
        $this->getServer()->getCommandMap()->register("ban", new BanCommand($this));
        $this->getServer()->getCommandMap()->register("banlist", new BanListCommand($this));
        $this->getServer()->getCommandMap()->register("unban", new UnBanCommand($this));
        $this->getServer()->getCommandMap()->register("banids", new BanIDListCommand($this));
    } 
}