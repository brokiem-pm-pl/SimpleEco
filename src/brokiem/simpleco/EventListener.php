<?php

declare(strict_types=1);

namespace brokiem\simpleco;

use brokiem\simpleco\api\EcoAPI;
use brokiem\simpleco\database\Query;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        SimpleEco::getInstance()->getDataConnector()->executeSelect(Query::SIMPLEECO_GET_XUID_BY_NAME, [
            "name" => $player->getName()
        ], function(array $rows) use ($player) {
            if (count($rows) === 0) {
                EcoAPI::addPlayer($player->getName(), $player->getXuid());
            }
        });
    }
}