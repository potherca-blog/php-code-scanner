<?php

namespace Potherca\Scanner\Identifier;

use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeType;

class NetworkFunctionsIdentifier extends AbstractSingleTypeIdentifier
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
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

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function getSupportedNodeTypes()
    {
        return [
            NodeType::EXPR_FUNC_CALL,
        ];
    }

    final public function getIdentityType()
    {
        return IdentityType::USAGE_INTERNAL_NETWORK_CALL;
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
