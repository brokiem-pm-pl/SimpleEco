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

final class MyMoneyCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("seco.mymoney")) {
            return;
        }

        if ($sender instanceof Player) {
            EconomyAPI::getXuidByName($sender->getName(), function(array $rows) use ($sender) {
                if (count($rows) >= 1) {
                    EconomyAPI::getMoney($rows[0]["xuid"], function(array $row) use ($sender) {
                        $sender->sendMessage("Your money is {$row[0]["money"]}");
                    });
                }
            });
        }
    }

    public function getOwningPlugin(): Plugin {
        return SimpleEco::getInstance();
    }
}