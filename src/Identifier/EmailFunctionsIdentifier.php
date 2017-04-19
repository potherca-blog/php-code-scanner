<?php

namespace Potherca\Scanner\Identifier;

use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeType;

class EmailFunctionsIdentifier extends AbstractSingleTypeIdentifier
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var array */
    private static $functions = [
        // @TODO: http://php.net/manual/en/refs.remote.mail.php
        //        Cyrus — Cyrus IMAP administration
        //        IMAP — IMAP, POP3 and NNTP
        /*/ Mail /*/
        'mail',
        //        Mailparse
        //        vpopmail

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
        return IdentityType::USAGE_INTERNAL_EMAIL_CALL;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function getIdentifiers()
    {
        return self::$functions;
    }
}

/*EOF*/
