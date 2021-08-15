<?php

declare(strict_types=1);

namespace brokiem\simpleco;

use brokiem\simpleco\api\EcoAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        // TODO: Advanced checking
        EcoAPI::addPlayer($player->getName(), $player->getXuid());
    }
}