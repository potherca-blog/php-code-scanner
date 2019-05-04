<?php

namespace Potherca\Scanner\Provider;

class DatabaseFunctionsProvider implements ProviderInterface
{
    /** @var array */
    private static $functions = [
        'mysql_connect',
        'mysql_create_db',
        'mysql_db_name',
        'mysql_db_query',
        'mysql_query',
        'mysql_select_db',
        'mysql_unbuffered_query',

        // @TODO: Mysqli -- http://php.net/manual/en/class.mysqli.php

        // @TODO: ODBC -- http://php.net/manual/en/ref.uodbc.php
        'odbc_connect',

        // @TODO: PDO -- http://php.net/manual/en/book.pdo.php
    ];

    /**
     * @return array
     */
    final public function provide()
    {
        return self::$functions;
    }
}

/*EOF*/
