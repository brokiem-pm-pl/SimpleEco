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

final class SetMoneyCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("seco.setmoney")) {
            return;
        }

        if (isset($args[1]) and is_numeric($args[1])) {
            $player = Server::getInstance()->getPlayerByPrefix($args[0]);

            if ($player !== null) {
                EcoAPI::setMoney($sender->getName(), $args[1], function() use ($sender, $args, $player) {
                    $player->sendMessage("Your money has been set to $args[1]");
                    $sender->sendMessage("Set money to $args[1] from {$player->getName()} success.");
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