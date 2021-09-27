<?php

declare(strict_types=1);

namespace brokiem\simpleco\api;

use brokiem\simpleco\database\Query;
use brokiem\simpleco\SimpleEco;

final class EconomyAPI {

    public static function checkMaxMoney(float $value): int {
        if ($value <= -99000000) {
            return -98000000;
        }

        if ($value >= 99000000) {
            return 98000000;
        }

        return (int)$value;
    }

    public static function reduceMoney(string $name, int $value, ?callable $onInserted = null): void {
        self::addMoney($name, self::checkMaxMoney(-(int)abs($value)), $onInserted);
    }

    public static function addMoney(string $name, int $value, ?callable $onInserted = null): void {
        self::getXuidByName($name, function(array $rows) use ($onInserted, $value) {
            if (count($rows) >= 1) {
                $xuid = $rows[0]["xuid"];

                self::getMoney($xuid, function(array $rows) use ($onInserted, $value, $xuid) {
                    $money = self::checkMaxMoney($rows[0]["money"]);

                    SimpleEco::getInstance()->getDataConnector()->executeInsert(Query::SIMPLEECO_SETMONEY, [
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

    public static function setMoney(string $name, int $value, ?callable $onInserted = null): void {
        self::getXuidByName($name, function(array $rows) use ($onInserted, $value) {
            if (count($rows) >= 1) {
                $xuid = $rows[0]["xuid"];

                SimpleEco::getInstance()->getDataConnector()->executeInsert(Query::SIMPLEECO_ADDMONEY, [
                    "xuid" => $xuid, "money" => self::checkMaxMoney($value), "extraData" => null
                ], $onInserted);
            }
        });
    }

    public static function getTopMoney(?callable $callable = null): void {
        SimpleEco::getInstance()->getDataConnector()->executeSelect(Query::SIMPLEECO_GETALLROW, [], $callable);
    }

    public static function addPlayer(string $name, string $xuid, ?callable $onInserted = null): void {
        SimpleEco::getInstance()->getDataConnector()->executeInsert(Query::SIMPLEECO_ADDXUID, [
            "name" => $name, "xuid" => $xuid, "extraData" => null
        ], function() use ($onInserted, $name) {
            self::setMoney($name, 0, $onInserted);
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