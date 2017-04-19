<?php

namespace Potherca\Scanner\Identifier;


use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeType;

class EnvironmentFunctionsIdentifier extends AbstractSingleTypeIdentifier
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var array */
    private static $functions = [
        'apache_getenv',
        'apache_setenv',
        'getenv',
        'putenv',
        'ini_alter',
        'ini_get',
        'ini_get_all',
        'ini_restore',
        'ini_set',
        'ini_set',

        // @TODO: Memcache / Memcached

        // @TODO: http://php.net/manual/en/refs.utilspec.server.php
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
        return IdentityType::USAGE_INTERNAL_ENVIRONMENT_CALL;
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
