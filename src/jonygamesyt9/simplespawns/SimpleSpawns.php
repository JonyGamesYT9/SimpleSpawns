<?php

namespace jonygamesyt9\simplespawns;

use jonygamesyt9\simplespawns\commands\LobbyCommand;
use jonygamesyt9\simplespawns\commands\SetLobbyCommand;
use jonygamesyt9\simplespawns\spawn\SpawnFactory;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class SimpleSpawns extends PluginBase {
    use SingletonTrait;

    public const CONFIG_FILE = "config.yml";

    private Config $config;

    public function onLoad(): void {
        self::setInstance($this);
        $this->config = new Config($this->getDataFolder() . self::CONFIG_FILE . Config::YAML);
    }

    public function onEnable(): void {
        $this->saveResource(self::CONFIG_FILE);
        $config = $this->getConfigFile();
        if ($config->get("version") === 4) {
            SpawnFactory::getInstance()->setLobbyMode($this->getLobbyMode($config->get("teleport.mode")));
            SpawnFactory::getInstance()->init();
            $this->registerPermission("simplespawns.command.setlobby");
            if ($config->get("only.teleport-with-permission") === "true") {
                $this->registerPermission("simplespawns.command.lobby");
            }
            if (SpawnFactory::getInstance()->getLobbyMode() === SpawnFactory::MODE_NORMAL) {
                $this->getServer()->getCommandMap()->registerAll("SimpleSpawns", [new LobbyCommand(), new SetLobbyCommand()]);
            } else if (SpawnFactory::getInstance()->getLobbyMode() === SpawnFactory::MODE_TRANSFER) {
                $this->getServer()->getCommandMap()->register("lobby", new LobbyCommand());
            }
        } else {
            $this->getLogger()->error("SimpleSpawns: Your config is from an old version, we recommend you delete it so that the most recent one can be installed.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function getConfigFile(): Config {
        return $this->config;
    }

    public function registerPermission(string $perms): void {
        $permission = new Permission($perms);
        PermissionManager::getInstance()->addPermission($permission);
    }

    public function getLobbyMode(string $mode): int {
        return match ($mode) {
            "normal" => SpawnFactory::MODE_NORMAL,
            "transfer" => SpawnFactory::MODE_TRANSFER,
            default => SpawnFactory::MODE_NORMAL
        };
    }
}