<?php

declare(strict_types=1);

namespace brokiem\simpleco\commands;

use brokiem\simpleco\api\EcoAPI;
use brokiem\simpleco\SimpleEco;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

final class SeeMoneyCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("seco.seemoney")) {
            return;
        }

        if (isset($args[1]) and is_numeric($args[1])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);

            if ($player !== null) {
                EcoAPI::getXuidByName($player->getName(), function(array $rows) use ($sender) {
                    if (count($rows) >= 1) {
                        EcoAPI::getMoney($rows[0]["xuid"], function(array $row) use ($sender) {
                            $sender->sendMessage("Your money is {$row[0]["money"]}");
                        });
                    }
                });
            } else {
                $sender->sendMessage(TextFormat::RED . "Player $args[0] not found or offline");
            }
        }
    }

    public function getOwningPlugin(): Plugin {
        return SimpleEco::getInstance();
    }
}