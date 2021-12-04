<?php

namespace JonyGamesYT9\SimpleSpawns\commands;

use JonyGamesYT9\SimpleSpawns\SimpleSpawns;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use function strtolower;
use function str_replace;

/**
 * Class SetLobbyCommand
 * @package JonyGamesYT9\SimpleSpawns\commands
 */
class SetLobbyCommand extends Command
{
  
  /** @var SimpleSpawns $plugin */
  private SimpleSpawns $plugin;
  
  /**
   * SetLobbyCommand constructor.
   * @param SimpleSpawns $plugin
   */
  public function __construct(SimpleSpawns $plugin) 
  {
    $this->plugin = $plugin;
    parent::__construct("setlobby", "Set the spawn for users to teleport", null, []);
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
      if ($sender->hasPermission("simplespawns.setlobby")) {
        $this->getPlugin()->getYamlProvider()->setWorld($sender->getWorld()->getFolderName());
        $this->getPlugin()->getYamlProvider()->setCoordinates("x", $sender->getPosition()->getX());
        $this->getPlugin()->getYamlProvider()->setCoordinates("y", $sender->getPosition()->getY());
        $this->getPlugin()->getYamlProvider()->setCoordinates("z", $sender->getPosition()->getZ());
        $sender->sendMessage(str_replace(["&", "{world}", "{x}", "{y}", "{z}"], ["§", $sender->getWorld()->getFolderName(), $sender->getPosition()->getX(), $sender->getPosition()->getY(), $sender->getPosition()->getZ()], $this->getPlugin()->getYamlProvider()->getMessage("place.hub.success")));
      } else {
        $sender->sendMessage(str_replace(["&"], ["§"], $this->getPlugin()->getYamlProvider()->getMessage("no.permissions")));
      }
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