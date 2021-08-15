<?php

/*
 * Auto-generated by libasynql-def
 * Created from sqlite.sql
 */

declare(strict_types=1);

namespace brokiem\simpleco\database;

interface Query {
    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:41
     *
     * <h3>Variables</h3>
     * - <code>:extraData</code> string, required in sqlite.sql
     * - <code>:money</code> float, required in sqlite.sql
     * - <code>:xuid</code> string, required in sqlite.sql
     */
    public const SIMPLEECO_ADDMONEY = "simpleeco.addmoney";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:41
     *
     * <h3>Variables</h3>
     * - <code>:extraData</code> string, required in sqlite.sql
     * - <code>:money</code> float, required in sqlite.sql
     * - <code>:xuid</code> string, required in sqlite.sql
     */
    public const SIMPLEECO_SETMONEY = "simpleeco.setmoney";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:60
     *
     * <h3>Variables</h3>
     * - <code>:extraData</code> string, required in sqlite.sql
     * - <code>:xuid</code> string, required in sqlite.sql
     * - <code>:name</code> string, required in sqlite.sql
     */
    public const SIMPLEECO_ADDXUID = "simpleeco.addxuid";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:51
     *
     * <h3>Variables</h3>
     * - <code>:xuid</code> string, required in sqlite.sql
     */
    public const SIMPLEECO_DELETEMONEY = "simpleeco.deletemoney";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:64
     *
     * <h3>Variables</h3>
     * - <code>:name</code> string, required in sqlite.sql
     */
    public const SIMPLEECO_DELETEXUID = "simpleeco.deletexuid";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:47
     *
     * <h3>Variables</h3>
     * - <code>:name</code> string, required in sqlite.sql
     */
    public const SIMPLEECO_GET_XUID_BY_NAME = "simpleeco.getxuidbyname";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:47
     *
     * <h3>Variables</h3>
     * - <code>:xuid</code> string, required in sqlite.sql
     */
    public const SIMPLEECO_GETMONEY = "simpleeco.getmoney";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:19
     */
    public const SIMPLEECO_INIT_DATA = "simpleeco.init.data";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:10
     */
    public const SIMPLEECO_INIT_INFO = "simpleeco.init.info";

    /**
     * <h4>Declared in:</h4>
     * - resources/sqlite.sql:28
     */
    public const SIMPLEECO_INIT_XUIDS = "simpleeco.init.xuids";

}
