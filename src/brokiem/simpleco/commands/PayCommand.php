<?php

declare(strict_types=1);

namespace brokiem\simpleco\commands;

use brokiem\simpleco\api\EconomyAPI;
use brokiem\simpleco\SimpleEco;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

final class PayCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("seco.pay")) {
            return;
        }

        if (isset($args[1]) and is_numeric($args[1]) and $sender instanceof Player) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);

            if ($player !== null) {
                EconomyAPI::getXuidByName($sender->getName(), function(array $rows) use ($player, $args, $sender) {
                    if (count($rows) >= 1) {
                        EconomyAPI::getMoney($rows[0]["xuid"], function(array $row) use ($player, $args, $sender) {
                            $senderMoney = $row[0]["money"];

                            if ($senderMoney >= (int)$args[1] and (int)$args[1] >= 1) {
                                if ((int)$args[1] >= 100000) {
                                    $player->sendMessage("Max transfer money is 100000");
                                    return;
                                }

                                EconomyAPI::reduceMoney($sender->getName(), (int)$args[1], function() use ($sender, $args, $player) {
                                    EconomyAPI::addMoney($player->getName(), (int)$args[1], function() use ($sender, $args, $player) {
                                        $player->sendMessage("You have been paid $args[1] by " . $sender->getName());
                                    });

                                    $sender->sendMessage("Paying " . (int)$args[1] . " to {$player->getName()} success.");
                                });
                            } else {
                                $sender->sendMessage(TextFormat::RED . "You don't have enough money!");
                            }
                        });
                    }
                });
            } else {
                $sender->sendMessage(TextFormat::RED . "Player $args[0] not found or offline");
            }
        } else {
            $sender->sendMessage(TextFormat::RED . "Usage: /pay <player> <value>");
        }
    }

    public function getOwningPlugin(): Plugin {
        return SimpleEco::getInstance();
    }
}