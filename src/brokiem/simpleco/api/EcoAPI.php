<?php

declare(strict_types=1);

namespace brokiem\simpleco\api;

use brokiem\simpleco\database\Query;
use brokiem\simpleco\SimpleEco;

final class EcoAPI {

    public static function reduceMoney(string $name, float|int $value, ?callable $onInserted = null): void {
        self::addMoney($name, -$value, $onInserted);
    }

    public static function addMoney(string $name, float|int $value, ?callable $onInserted = null): void {
        self::getXuidByName($name, function(array $rows) use ($onInserted, $value) {
            if (count($rows) >= 1) {
                $xuid = $rows[0]["xuid"];

                self::getMoney($xuid, function(array $rows) use ($onInserted, $value, $xuid) {
                    $money = $rows[0]["money"];

                    SimpleEco::getInstance()->getDataConnector()->executeInsert(Query::SIMPLEECO_ADDMONEY, [
                        "xuid" => $xuid, "money" => $money + $value, "extraData" => null
                    ], $onInserted);
                });
            }
        });
    }

    public static function getXuidByName(string $name, $callable): void {
        SimpleEco::getInstance()->getDataConnector()->executeSelect(Query::SIMPLEECO_GET_XUID_BY_NAME, [
            "name" => $name
        ], $callable);
    }

    public static function getMoney(string $xuid, $callable): void {
        SimpleEco::getInstance()->getDataConnector()->executeSelect(Query::SIMPLEECO_GETMONEY, [
            "xuid" => $xuid
        ], $callable);
    }

    public static function setMoney(string $name, float|int $value, ?callable $onInserted = null): void {
        self::getXuidByName($name, function(array $rows) use ($onInserted, $value) {
            if (count($rows) >= 1) {
                $xuid = $rows[0]["xuid"];

                SimpleEco::getInstance()->getDataConnector()->executeInsert(Query::SIMPLEECO_ADDMONEY, [
                    "xuid" => $xuid, "money" => $value, "extraData" => null
                ], $onInserted);
            }
        });
    }

    public static function addPlayer(string $name, string $xuid, ?callable $onInserted = null): void {
        SimpleEco::getInstance()->getDataConnector()->executeInsert(Query::SIMPLEECO_ADDXUID, [
            "name" => $name, "xuid" => $xuid, "extraData" => null
        ], function() use ($onInserted, $name) {
            self::addMoney($name, 0, $onInserted);
        });
    }

    public static function removePlayer(string $name, ?callable $onSuccess = null): void {
        self::getXuidByName($name, function(array $rows) use ($onSuccess, $name) {
            if (count($rows) >= 1) {
                $xuid = $rows[0]["xuid"];

                SimpleEco::getInstance()->getDataConnector()->executeGeneric(Query::SIMPLEECO_DELETEMONEY, [
                    "xuid" => $xuid
                ], function() use ($onSuccess, $name) {
                    SimpleEco::getInstance()->getDataConnector()->executeGeneric(Query::SIMPLEECO_DELETEXUID, [
                        "name" => $name
                    ], $onSuccess);
                });
            }
        });
    }
}