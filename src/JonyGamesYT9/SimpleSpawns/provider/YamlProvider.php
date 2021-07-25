<?php

namespace JonyGamesYT9\SimpleSpawns\provider;

use JonyGamesYT9\SimpleSpawns\SimpleSpawns;
use pocketmine\utils\Config;

/**
 * Class YamlProvider
 * @package JonyGamesYT9\SimpleSpawns\provider
 */
class YamlProvider
{
  
  /** @var SimpleSpawns $plugin */
  private $plugin;
  
  /** @var Config $config */
  private $config;
  
  /**
   * YamlProvider constructor.
   * @param SimpleSpawns $plugin
   */
  public function __construct(SimpleSpawns $plugin)
  {
    $this->plugin = $plugin;
    $this->config = new Config($this->getPlugin()->getDataFolder() . "Config.yml", Config::YAML);
  }
  
  /**
   * @return int
   */
  public function getConfigVersion(): int 
  {
    return $this->config->get("version");
  }
  
  /**
   * @return string
   */
  public function getWorld(): string
  {
    return $this->config->get("world");
  }
  
  /**
   * @param string $world
   * @return void
   */
  public function setWorld(string $world): void 
  {
    $this->config->set("world", $world);
    $this->config->save();
  }
  
  /**
   * @param string $coordinates
   * @return int
   */
  public function getCoordinates(string $coordinates): int
  {
    $position = 0;
    switch (strtolower($coordinates)) {
      case "x":
        $position = $this->config->get("x");
        break;
      case "y":
        $position = $this->config->get("y");
        break;
      case "z":
        $position = $this->config->get("z");
        break;
    }
    return $position;
  }
  
  /**
   * @param string $type
   * @param int $coordinates
   * @return void
   */
  public function setCoordinates(string $type, int $coordinates): void 
  {
    switch (strtolower($type)) {
      case "x":
        $this->config->set("x", $coordinates);
        $this->config->save();
        break;
      case "y":
        $this->config->set("y", $coordinates);
        $this->config->save();
        break;
      case "z":
        $this->config->set("z", $coordinates);
        $this->config->save();
        break;
    }
  }
  
  /**
   * @param string $format
   * @return string
   */
  public function getMessage(string $format): string 
  {
    return $this->config->get($format);
  }
  
  /**
   * @return SimpleSpawns
   */
  public function getPlugin(): SimpleSpawns
  {
    return $this->plugin;
  }
}