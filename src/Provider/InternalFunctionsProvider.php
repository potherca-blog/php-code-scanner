<?php

namespace Potherca\Scanner\Provider;

use Potherca\Scanner\Provider\InternalFunctions\Php56;
use Potherca\Scanner\Provider\InternalFunctions\Php70;

class InternalFunctionsProvider implements ProviderInterface
{
    const VERSION_56 = '56';
    const VERSION_70 = '70';

    /** @var string|int|float */
    private $phpVersion;

    final public function __construct($phpVersion)
    {
        $this->phpVersion = $phpVersion;
    }

    /**
     * @return array
     */
    final public function provide()
    {
        static $functions;

        if ($functions === null) {
            $class = sprintf('%s\\InternalFunctions\\Php%s', __NAMESPACE__, $this->phpVersion);

            /** @var Php56|Php70 $internalProvider */
            $internalProvider = new $class();
            $functions = $internalProvider->provide();
        }

        return $functions;
    }
}

/*EOF*/
