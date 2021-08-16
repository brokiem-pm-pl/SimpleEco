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

final class GiveMoneyCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("seco.givemoney")) {
            return;
        }

        if (isset($args[1]) and is_numeric($args[1])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);

            if ($player !== null) {
                EconomyAPI::addMoney($player->getName(), (int)$args[1], function() use ($sender, $args, $player) {
                    $player->sendMessage("You have been given $args[1]");
                    $sender->sendMessage("Giving " . (int)$args[1] . " to {$player->getName()} success.");
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