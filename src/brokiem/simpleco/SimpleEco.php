<?php

declare(strict_types=1);

namespace brokiem\simpleco;

use brokiem\simpleco\api\EcoAPI;
use brokiem\simpleco\commands\GiveMoneyCommand;
use brokiem\simpleco\commands\MyMoneyCommand;
use brokiem\simpleco\commands\PayCommand;
use brokiem\simpleco\commands\SeeMoneyCommand;
use brokiem\simpleco\commands\SetMoneyCommand;
use brokiem\simpleco\commands\TakeMoneyCommand;
use brokiem\simpleco\database\Query;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;

final class SimpleEco extends PluginBase {
    use SingletonTrait;

    private DataConnector $dataConnector;

    protected function onEnable(): void {
        $this->init();
    }

    private function init(): void {
        self::setInstance($this);

        $this->saveDefaultConfig();
        $this->dataConnector = libasynql::create($this, $this->getConfig()->get("database"), [
            "sqlite" => "sqlite.sql"
        ]);

        $this->dataConnector->executeGeneric(Query::SIMPLEECO_INIT_INFO);
        $this->dataConnector->executeGeneric(Query::SIMPLEECO_INIT_DATA);
        $this->dataConnector->executeGeneric(Query::SIMPLEECO_INIT_XUIDS);
        $this->dataConnector->waitAll();

        (new EcoAPI($this->dataConnector));

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->registerAll("seco", [
            new GiveMoneyCommand("givemoney", "Give money to player"),
            new MyMoneyCommand("mymoney", "See my money"),
            new PayCommand("pay", "Pay money to other player", "/pay <player> <value>"),
            new SeeMoneyCommand("seemoney", "See player money", "/seemoney <player>"),
            new SetMoneyCommand("setmoney", "Set player money"),
            new TakeMoneyCommand("takemoney", "Take player money")
        ]);
    }

    public function getDataConnector(): DataConnector {
        return $this->dataConnector;
    }

    protected function onDisable(): void {
        if ($this->dataConnector instanceof DataConnector) {
            $this->dataConnector->waitAll();
            $this->dataConnector->close();
        }
    }
}