<?php

namespace jonygamesyt9\simplespawns\spawn;

use jonygamesyt9\simplespawns\SimpleSpawns;
use JsonException;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;
use pocketmine\world\World;

class SpawnFactory {
    use SingletonTrait;

    public const MODE_NORMAL = 0;
    public const MODE_TRANSFER = 1;

    private Spawn $spawn;

    private int $mode;

    private Config $config;

    public function init(): void {
        $this->config = new Config(SimpleSpawns::getInstance()->getDataFolder() . "spawn.yml", Config::YAML);
        if ($this->getLobbyMode() === self::MODE_NORMAL) {
            if ($this->getConfig()->exists("position") and $this->getConfig()->exists("world")) {
                $positionToArray = explode(":", $this->getConfig()->get("position"));
                $positionToVector = new Vector3((int)$positionToArray[0], (int)$positionToArray[1], (int)$positionToArray[2]);
                if (!Server::getInstance()->getWorldManager()->isWorldLoaded($this->getConfig()->get("world"))) {
                    Server::getInstance()->getWorldManager()->loadWorld($this->getConfig()->get("world"));
                }
                $world = Server::getInstance()->getWorldManager()->getWorld($this->getConfig()->get("world"));
                $this->add(new Spawn($world, $positionToVector));
            } else {
                SimpleSpawns::getInstance()->getLogger()->info("SimpleSpawns: No spawn detected in the system, remember to place one.");
            }
        } else if ($this->getLobbyMode() === self::MODE_TRANSFER) {
            SimpleSpawns::getInstance()->getLogger()->info("SimpleSpawns: The transfer method has been detected, it is not necessary to load anything..");
        }
    }

    /**
     * @throws JsonException
     */
    public function create(World $world, Vector3 $position): void {
        $config = $this->getConfig();
        $config->setAll(["world" => $world->getFolderName(), "position" => $position->getX() . ":" . $position->getY() . ":" . $position->getZ()]);
        $config->save();
        $this->add(new Spawn($world, $position));
    }

    public function add(Spawn $spawn): void {
        $this->spawn = $spawn;
    }

    public function canTeleport(): bool {
        return !is_null($this->getConfig()->get("world")) and !is_null($this->getConfig()->get("position"));
    }

    public function teleport(Player $player): void {
        if ($this->getLobbyMode() === self::MODE_NORMAL) {
            if ($this->canTeleport()) {
                if (SimpleSpawns::getInstance()->getConfigFile()->get("only.teleport-with-permission") === "false") {
                    $spawn = $this->getSpawn();
                    $player->teleport(new Position($spawn->getVector3()->getX(), $spawn->getVector3()->getY(), $spawn->getVector3()->getZ(), $spawn->getWorld()));
                    $player->sendMessage(str_replace(["&"], ["§"], SimpleSpawns::getInstance()->getConfigFile()->get("message.teleport.success")));
                } else if (SimpleSpawns::getInstance()->getConfigFile()->get("only.teleport-with-permission") === "true") {
                    if ($player->hasPermission("simplespawns.command.lobby")) {
                        $spawn = $this->getSpawn();
                        $player->teleport(new Position($spawn->getVector3()->getX(), $spawn->getVector3()->getY(), $spawn->getVector3()->getZ(), $spawn->getWorld()));
                        $player->sendMessage(str_replace(["&"], ["§"], SimpleSpawns::getInstance()->getConfigFile()->get("message.teleport.success")));
                    } else {
                        $player->sendMessage(str_replace(["&"], ["§"], SimpleSpawns::getInstance()->getConfigFile()->get("message.command.nopermissions")));
                    }
                }
            } else {
                $player->sendMessage(str_replace(["&"], ["§"], SimpleSpawns::getInstance()->getConfigFile()->get("message.teleport.error")));
            }
        } else if ($this->getLobbyMode() === self::MODE_TRANSFER) {
            $ipToArray = explode(":", SimpleSpawns::getInstance()->getConfigFile()->get("teleport.transfer.address"));
            $player->transfer($ipToArray[0], (int)$ipToArray[1], str_replace(["&"], ["§"], SimpleSpawns::getInstance()->getConfigFile()->get("message.teleport.success")));
        }
    }

    public function setLobbyMode(int $mode): void {
        $this->mode = $mode;
    }

    public function getLobbyMode(): int {
        return $this->mode;
    }

    public function getConfig(): Config {
        return $this->config;
    }

    public function getSpawn(): ?Spawn {
        return $this->spawn ?? null;
    }
}