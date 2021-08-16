<?php

declare(strict_types=1);

namespace brokiem\simpleco\commands;

use brokiem\simpleco\api\EconomyAPI;
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

        if (isset($args[0])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);

            if ($player !== null) {
                EconomyAPI::getXuidByName($player->getName(), function(array $rows) use ($player, $sender) {
                    if (count($rows) >= 1) {
                        EconomyAPI::getMoney($rows[0]["xuid"], function(array $row) use ($player, $sender) {
                            $sender->sendMessage($player->getName() . " money is {$row[0]["money"]}");
                        });
                    }
                });
            } else {
                $sender->sendMessage(TextFormat::RED . "Player $args[0] not found or offline");
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "Usage: /seemoney <player>");
        }
    }

    public function getOwningPlugin(): Plugin {
        return SimpleEco::getInstance();
    }
}