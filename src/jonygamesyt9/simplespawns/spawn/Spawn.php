<?php

namespace jonygamesyt9\simplespawns\spawn;

use pocketmine\math\Vector3;
use pocketmine\world\World;

class Spawn {

    private World $world;

    private Vector3 $vector3;

    public function __construct(World $world, Vector3 $vector3) {
        $this->world = $world;
        $this->vector3 = $vector3;
    }

    public function getWorld(): ?World {
        return $this->world;
    }

    public function getVector3(): ?Vector3 {
        return $this->vector3;
    }
}