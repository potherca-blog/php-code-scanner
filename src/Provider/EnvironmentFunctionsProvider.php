<?php

namespace Potherca\Scanner\Provider;

class EnvironmentFunctionsProvider implements ProviderInterface
{
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

    /**
     * @return array
     */
    final public function provide()
    {
        return self::$functions;
    }
}

/*EOF*/
