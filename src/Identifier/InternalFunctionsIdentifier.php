<?php

namespace Potherca\Scanner\Identifier;

use Potherca\Scanner\Identifier\InternalFunctions\Php56;
use Potherca\Scanner\Identifier\InternalFunctions\Php70;
use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeType;

class InternalFunctionsIdentifier extends AbstractSingleTypeIdentifier
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const VERSION_56 = '56';
    const VERSION_70 = '70';

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function getSupportedNodeTypes()
    {
        return [
            NodeType::EXPR_FUNC_CALL,
        ];
    }

    final public function getIdentityType()
    {
        return IdentityType::USAGE_INTERNAL_FUNCTION;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return array
     */
    final public function getIdentifiers()
    {
        static $functions;

        if ($functions === null) {

            $phpVersion = $this->getOption(IdentifierOption::PHP_VERSION);

            $class = sprintf('%s\\InternalFunctions\\Php%s', __NAMESPACE__, $phpVersion);

            /** @var Php56|Php70 $internalIdentifier */
            $internalIdentifier = new $class($this->getOptions());
            $functions = $internalIdentifier->getIdentifiers();
        }

        return $functions;
    }
}

/*EOF*/
