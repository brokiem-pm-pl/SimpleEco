<?php

declare(strict_types=1);

namespace brokiem\simpleco;

use brokiem\simpleco\api\EconomyAPI;
use brokiem\simpleco\database\Query;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;

class EventListener implements Listener {

    public function onJoin(PlayerLoginEvent $event): void {
        $player = $event->getPlayer();

        if ($player->isConnected()) {
            SimpleEco::getInstance()->getDataConnector()->executeSelect(Query::SIMPLEECO_GET_XUID_BY_NAME, [
                "name" => $player->getName()
            ], function(array $rows) use ($player) {
                if (count($rows) === 0) {
                    EconomyAPI::addPlayer($player->getName(), $player->getXuid());
                }
            });
        }
    }
}