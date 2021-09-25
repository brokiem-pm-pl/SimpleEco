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

final class TopMoneyCommand extends Command implements PluginOwned {

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("seco.topmoney")) {
            return;
        }

        if ($sender instanceof Player) {
            EconomyAPI::getTopMoney(function(array $rows) use ($sender) {
                foreach ($rows as $row) {
                    EconomyAPI::getMoney($row["xuid"], function(array $moneyRow) use ($sender, $row) {
                        $name = $row["xuid"];

                        $i = 0;
                        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                            if ($i >= 10) {
                                break;
                            }

                            if ($onlinePlayer->getXuid() === $name) {
                                $name = $onlinePlayer->getName();
                                break;
                            }

                            $i++;
                        }

                        $string = $name . " -> " . $moneyRow[0]["money"] . "";
                        $sender->sendMessage($string);
                    });
                }
            });
        }
    }

    public function getOwningPlugin(): Plugin {
        return SimpleEco::getInstance();
    }
}