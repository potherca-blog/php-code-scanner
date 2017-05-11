<?php

namespace Potherca\Scanner\Identifier;

trait SupportsNodeTypeTrait
{
    final public function supportsNodeType($nodeType)
    {
        return in_array($nodeType, $this->getSupportedNodeTypes(), true);
    }
}

/*EOF*/
