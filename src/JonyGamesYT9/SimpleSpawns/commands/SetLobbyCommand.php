<?php

namespace JonyGamesYT9\SimpleSpawns\commands;

use JonyGamesYT9\SimpleSpawns\SimpleSpawns;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use function strtolower;
use function str_replace;

/**
 * Class SetLobbyCommand
 * @package JonyGamesYT9\SimpleSpawns\commands
 */
class SetLobbyCommand extends Command implements PluginIdentifiableCommand 
{
  
  /** @var SimpleSpawns $plugin */
  private $plugin;
  
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
      if ($sender->hasPermission("spawns.command.setlobby")) {
        $this->getPlugin()->getYamlProvider()->setWorld($sender->getLevel()->getFolderName());
        $this->getPlugin()->getYamlProvider()->setCoordinates("x", $sender->getX());
        $this->getPlugin()->getYamlProvider()->setCoordinates("y", $sender->getY());
        $this->getPlugin()->getYamlProvider()->setCoordinates("z", $sender->getZ());
        $sender->sendMessage(str_replace(["&", "{world}", "{x}", "{y}", "{z}"], ["§", $sender->getLevel()->getFolderName(), $sender->getX(), $sender->getY(), $sender->getZ()], $this->getPlugin()->getYamlProvider()->getMessage("place.hub.success")));
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