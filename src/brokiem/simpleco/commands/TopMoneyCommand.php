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
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

final class TopMoneyCommand extends Command implements PluginOwned {

    private array $newArray = [];

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender->hasPermission("seco.topmoney")) {
            return;
        }

        if ($sender instanceof Player) {
            EconomyAPI::getTopMoney(function(array $rows) {
                foreach ($rows as $row) {
                    EconomyAPI::getMoney($row["xuid"], function(array $moneyRow) use ($row) {
                        $this->newArray[$row["xuid"]] = (int)$moneyRow[0]["money"];
                    });
                }
            });

            SimpleEco::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($sender): void {
                $count = count($this->newArray);
                $text = "";
                arsort($this->newArray);
                $i = 1;
                foreach ($this->newArray as $name => $val) {
                    foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
                        if ($onlinePlayer->getXuid() === (string)$name) {
                            $name = $onlinePlayer->getName();
                            break;
                        }
                    }

                    $text .= "$count| $name -> $val\n";
                    if ($i > 9) {
                        break;
                    }
                    ++$i;
                }

                $sender->sendMessage($text);
            }), 120);
            $sender->sendMessage("Processing top money, please wait....");
        }
    }

    public function getOwningPlugin(): Plugin {
        return SimpleEco::getInstance();
    }
}