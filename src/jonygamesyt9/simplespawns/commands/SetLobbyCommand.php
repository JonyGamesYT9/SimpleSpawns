<?php

namespace jonygamesyt9\simplespawns\commands;

use jonygamesyt9\simplespawns\SimpleSpawns;
use jonygamesyt9\simplespawns\spawn\SpawnFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;

class SetLobbyCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("setlobby", "Set the spawn for users to teleport", null, []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            if ($sender->hasPermission("simplespawns.command.setlobby") or Server::getInstance()->isOp($sender->getName())) {
                SpawnFactory::getInstance()->create($sender->getWorld(), $sender->getPosition());
                $sender->sendMessage(str_replace(["&", "{world}", "{x}", "{y}", "{z}"], ["§", $sender->getWorld()->getFolderName(), $sender->getPosition()->getX(), $sender->getPosition()->getY(), $sender->getPosition()->getZ()], "§l§7SimpleSpawns | §r§7You placed the spawn correctly at the coordinates: World: §7{world}§f, X: §7{x}§f, Y: §7{y}§f, Z: §7{z}"));
            } else {
                $sender->sendMessage(str_replace(["&"], ["§"], SimpleSpawns::getInstance()->getConfigFile()->get("message.command.nopermissions")));
            }
        } else {
            $sender->sendMessage("§l§7SimpleSpawns | §r§fYou can only run this command from the game.");
        }
    }

    public function getOwningPlugin(): Plugin {
        return SimpleSpawns::getInstance();
    }
}