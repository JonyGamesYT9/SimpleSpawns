<?php

namespace JonyGamesYT9\SimpleSpawns\commands;

use JonyGamesYT9\SimpleSpawns\SimpleSpawns;
use JonyGamesYT9\SimpleSpawns\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

/**
 * Class LobbyCommand
 * @package JonyGamesYT9\SimpleSpawns\commands
 */
class LobbyCommand extends Command implements PluginIdentifiableCommand 
{
  
  /** @var SimpleSpawns $plugin */
  private $plugin;
  
  /**
   * LobbyCommand constructor.
   * @param SimpleSpawns $plugin
   */
  public function __construct(SimpleSpawns $plugin) 
  {
    $this->plugin = $plugin;
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
   * @return SimpleSpawns
   */
  public function getPlugin(): SimpleSpawns
  {
    return $this->plugin;
  }
}