-- # !mysql
-- # {simpleeco
-- #    {init
-- #        {info
CREATE TABLE IF NOT EXISTS simpleeco_info
(
    id         TINYINT UNSIGNED PRIMARY KEY,
    db_version TINYINT UNSIGNED NOT NULL DEFAULT 1
);
-- #        }
-- #        {data
CREATE TABLE IF NOT EXISTS simpleeco_data
(
    id        INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
    xuid      VARCHAR(32) NOT NULL,
    money     FLOAT,
    extraData TEXT
);
-- #        }
-- #        {xuids
CREATE TABLE IF NOT EXISTS simpleeco_xuids
(
    id        INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
    xuid      VARCHAR(32) NOT NULL,
    name      VARCHAR(32) NOT NULL,
    extraData TEXT
);
-- #        }
-- #    }


-- #    {addmoney
-- #        :xuid string
-- #        :money float
-- #        :extraData string
INSERT INTO simpleeco_data (xuid, money, extraData)
VALUES (:xuid, :money, :extraData)
ON DUPLICATE KEY UPDATE xuid      = VALUES(xuid),
                        money     = VALUES(money),
                        extraData = VALUES(extraData);
-- #    }
-- #    {getmoney
-- #        :xuid string
SELECT *
FROM simpleeco_data
WHERE xuid = :xuid;
-- #    }
-- #    {deletemoney
-- #        :xuid string
DELETE
FROM simpleeco_data
WHERE xuid = :xuid;
-- #    }


-- #    {addxuid
-- #        :xuid string
-- #        :name string
-- #        :extraData string
INSERT INTO simpleeco_xuids (xuid, name, extraData)
VALUES (:xuid, :name, :extraData)
ON DUPLICATE KEY UPDATE xuid      = VALUES(xuid),
                        name      = VALUES(name),
                        extraData = VALUES(extraData);
-- #    }
-- #    {deletexuid
-- #        :name string
DELETE
FROM simpleeco_xuids
WHERE name = :name;
-- #    }
-- # }