<?php

namespace Potherca\Scanner\Provider;

class EmailFunctionsProvider implements ProviderInterface
{
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

    /**
     * @return array
     */
    final public function provide()
    {
        return self::$functions;
    }
}

/*EOF*/
