<?php

namespace Potherca\Scanner\Provider\FileFunctionsProvider;

use Potherca\Scanner\Provider\ProviderInterface;

class ImageMagickFunctionsProvider implements ProviderInterface
{
    /** @var array */
    private static $functions = [
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
