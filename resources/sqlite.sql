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
-- #    }
-- #    {add
-- #        :xuid string
-- #        :money float
-- #        :extraData string
INSERT INTO simpleeco_data (xuid, money, extraData)
VALUES (:xuid, :money, :extraData)
ON DUPLICATE KEY UPDATE xuid      = VALUES(xuid),
                        money     = VALUES(money),
                        extraData = VALUES(extraData);
-- #    }
-- #    {get
-- #        :xuid string
SELECT *
FROM simpleeco_data
WHERE xuid = :xuid;
-- #    }
-- # }