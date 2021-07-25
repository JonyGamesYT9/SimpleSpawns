<?php

namespace JonyGamesYT9\SimpleSpawns;

use JonyGamesYT9\SimpleSpawns\provider\YamlProvider;
use JonyGamesYT9\SimpleSpawns\commands\LobbyCommand;
use JonyGamesYT9\SimpleSpawns\commands\SetLobbyCommand;
use pocketmine\plugin\PluginBase;

/**
* Class SimpleSpawns
* @package JonyGamesYT9\SimpleSpawns
*/
class SimpleSpawns extends PluginBase
{

  /** @var SimpleSpawns $instance */
  private static $instance;

  /** @var YamlProvider $provider */
  private $provider;

  /**
  * @return void
  */
  public function onLoad(): void
  {
    $this->saveResource("Config.yml");
    self::$instance = $this;
    $this->provider = new YamlProvider($this);
  }

  /**
  * @return void
  */
  public function onEnable(): void
  {
    $provider = $this->getYamlProvider();
    if ($provider->getConfigVersion() === 1) {
      if ($provider->getWorld() !== null) {
        $this->getServer()->loadLevel($provider->getWorld());
      } 
      $commands = [new LobbyCommand($this),
        new SetLobbyCommand($this)];
      foreach ($commands as $command) {
        $this->getServer()->getCommandMap()->register("simplespawns", $command);
      }
    } else {
      $this->getLogger()->error("SimpleSpawns: Error in config.yml please delete file and restart server!");
      $this->getServer()->getPluginManager()->disablePlugin($this);
    }
  }

  /**
  * @return YamlProvider
  */
  public function getYamlProvider(): YamlProvider
  {
    return $this->provider;
  }

  /**
  * @return SimpleSpawns
  */
  public static function getInstance(): SimpleSpawns
  {
    return self::$instance;
  }
}