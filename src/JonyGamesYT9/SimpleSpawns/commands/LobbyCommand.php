<?php

namespace jonygamesyt9\simplespawns\commands;

use jonygamesyt9\simplespawns\SimpleSpawns;
use jonygamesyt9\simplespawns\spawn\SpawnFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;

class LobbyCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("lobby", "Teleport to the server lobby.", null, ["hub", "spawn"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            SpawnFactory::getInstance()->teleport($sender);
        } else {
            $sender->sendMessage("§l§7SimpleSpawns | §r§fYou can only run this command from the game.");
        }
    }

    public function getOwningPlugin(): Plugin {
        return SimpleSpawns::getInstance();
    }
}