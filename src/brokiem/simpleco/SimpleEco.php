<?php

declare(strict_types=1);

namespace brokiem\simpleco;

use brokiem\simpleco\database\Query;
use pocketmine\plugin\PluginBase;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;

class SimpleEco extends PluginBase {

    private DataConnector $dataConnector;

    protected function onEnable(): void {
        $this->init();
    }

    private function init(): void {
        $this->saveDefaultConfig();
        $this->dataConnector = libasynql::create($this, $this->getConfig()->get("database"), [
            "sqlite" => "sqlite.sql"
        ]);

        $this->dataConnector->executeGeneric(Query::SIMPLEECO_INIT_INFO);
        $this->dataConnector->executeGeneric(Query::SIMPLEECO_INIT_DATA);
        $this->dataConnector->executeGeneric(Query::SIMPLEECO_INIT_XUIDS);
        $this->dataConnector->waitAll();
    }

    public function getDataConnector(): DataConnector {
        return $this->dataConnector;
    }
}