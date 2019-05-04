<?php

namespace Potherca\Scanner\Provider;

class NetworkFunctionsProvider implements ProviderInterface
{
    /** @var array */
    private static $functions = [
        /* cUrl */
        'curl_init',
        'curl_setopt',
        'curl_setopt_array',

        /* FTP */
        'ftp_connect',

        /* Native Network Functions */
        // @TODO: http://php.net/manual/en/ref.network.php
        'checkdnsrr',
        'dns_check_record',
        'dns_get_mx',
        'dns_get_record',
        'gethostbyaddr',
        'gethostbyname',
        'gethostbynamel',
        'gethostname',
        'getmxrr',

        /* Sockets */
        // @TODO: http://php.net/manual/en/ref.sockets.php
        'fsockopen',
        'socket_create',

        // @TODO: Web Services -- http://php.net/manual/en/refs.webservice.php
        /*/ OAuth /*/
        /*/ SCA /*/
        /*/ SOAP /*/
        /*/ Yar — Yet Another RPC Framework /*/
        /*/ XML-RPC /*/
        /*/  /*/
        /*/  /*/
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
