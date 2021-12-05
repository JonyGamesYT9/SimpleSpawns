<?php

namespace JonyGamesYT9\SimpleSpawns\commands;

use JonyGamesYT9\SimpleSpawns\SimpleSpawns;
use JonyGamesYT9\SimpleSpawns\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;

/**
 * Class LobbyCommand
 * @package JonyGamesYT9\SimpleSpawns\commands
 */
class LobbyCommand extends Command implements PluginOwned
{
  
  /**
   * LobbyCommand constructor.
   * @param SimpleSpawns $plugin
   */
  public function __construct(SimpleSpawns $plugin) 
  {
    parent::__construct("lobby", "Teleport to the server lobby.", null, ["hub", "spawn"]);
  }
  
  /**
   * @param CommandSender $sender
   * @param string $label 
   * @param array $args 
   * @return mixed|void 
   */
  public function execute(CommandSender $sender, string $label, array $args) 
  {
    if ($sender instanceof Player) {
      Utils::teleport($sender);
    } else {
      $sender->sendMessage("§l§7SimpleSpawns | §r§fYou can only run this command from the game.");
    }
  }
  
  /**
   * @return Plugin
   */
  public function getOwningPlugin(): Plugin
  {
    return SimpleSpawns::getInstance();
  }
}