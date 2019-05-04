<?php

namespace Potherca\Scanner\Provider\FileFunctionsProvider;

use Potherca\Scanner\Provider\ProviderInterface;

class CompressionFunctionsProvider implements ProviderInterface
{
    /** @var array */
    private static $functions = [
        // @TODO: http://php.net/manual/en/refs.compression.php

        /*/ Bzip2 /*/
        /*/ LZF /*/
        /*/ Phar /*/
        /*/ Rar — Rar Archiving /*/
        /*/ Zip /*/
        /*/ Zlib — Zlib Compression /*/

        /*/ Create /*/

        /*/ Read /*/

        /*/ Update /*/

        /*/ Delete /*/
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
