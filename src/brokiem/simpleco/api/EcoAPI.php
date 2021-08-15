<?php

declare(strict_types=1);

namespace brokiem\simpleco\api;

use brokiem\simpleco\database\Query;
use poggit\libasynql\DataConnector;

final class EcoAPI {

    private static DataConnector $connector;

    public function __construct(DataConnector $connector) {
        self::$connector = $connector;
    }

    public static function reduceMoney(string $name, float|int $value, ?callable $onInserted = null): void {
        self::addMoney($name, -$value, $onInserted);
    }

    public static function addMoney(string $name, float|int $value, ?callable $onInserted = null): void {
        self::getXuidByName($name, function(array $rows) use ($onInserted, $value) {
            if (count($rows) >= 1) {
                $xuid = $rows[0]["xuid"];

                self::getMoney($xuid, function(array $rows) use ($onInserted, $value, $xuid) {
                    $money = $rows[0]["money"];

                    self::$connector->executeInsert(Query::SIMPLEECO_ADDMONEY, [
                        "xuid" => $xuid, "money" => $money + $value, "extraData" => null
                    ], $onInserted);
                });
            }
        });
    }

    public static function getXuidByName(string $name, $callable): void {
        self::$connector->executeSelect(Query::SIMPLEECO_GET_XUID_BY_NAME, [
            "name" => $name
        ], $callable);
    }

    public static function getMoney(string $xuid, $callable): void {
        self::$connector->executeSelect(Query::SIMPLEECO_GETMONEY, [
            "xuid" => $xuid
        ], $callable);
    }

    public static function setMoney(string $name, float|int $value, ?callable $onInserted = null): void {
        self::getXuidByName($name, function(array $rows) use ($onInserted, $value) {
            if (count($rows) >= 1) {
                $xuid = $rows[0]["xuid"];

                self::$connector->executeInsert(Query::SIMPLEECO_ADDMONEY, [
                    "xuid" => $xuid, "money" => $value, "extraData" => null
                ], $onInserted);
            }
        });
    }

    public static function addPlayer(string $name, string $xuid, ?callable $onInserted = null): void {
        self::$connector->executeInsert(Query::SIMPLEECO_ADDXUID, [
            "name" => $name, "xuid" => $xuid, "extraData" => null
        ], function() use ($onInserted, $name) {
            self::addMoney($name, 0, $onInserted);
        });
    }

    public static function removePlayer(string $name, ?callable $onSuccess = null): void {
        self::getXuidByName($name, function(array $rows) use ($onSuccess, $name) {
            if (count($rows) >= 1) {
                $xuid = $rows[0]["xuid"];

                self::$connector->executeGeneric(Query::SIMPLEECO_DELETEMONEY, [
                    "xuid" => $xuid
                ], function() use ($onSuccess, $name) {
                    self::$connector->executeGeneric(Query::SIMPLEECO_DELETEXUID, [
                        "name" => $name
                    ], $onSuccess);
                });
            }
        });
    }
}