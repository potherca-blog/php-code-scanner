<?php

namespace Potherca\Scanner\Identifier;

use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeType;

class DatabaseFunctionsIdentifier extends AbstractSingleTypeIdentifier
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
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

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function getSupportedNodeTypes()
    {
        return [
            NodeType::EXPR_FUNC_CALL,
        ];
    }

    final public function getIdentityType()
    {
        return IdentityType::USAGE_INTERNAL_DATABASE_CALL;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return array
     */
    final public function getIdentifiers()
    {
        return self::$functions;
    }
}

/*EOF*/
