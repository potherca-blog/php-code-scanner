<?php

namespace Potherca\Scanner\Identifier;

use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeType;

class OutputIdentifier extends AbstractSingleTypeIdentifier
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var array */
    private static $functions = [
        /*/ Direct output /*/
        'echo',
        'flush',
        'ob_end_flush',
        'ob_flush',
        'passthru',
        'print',
        'printf',
        'system',
        'vprintf',

        /*/ Possbile output depending on Stream /*/
        // @TODO: Add logic to analyze stream (for instance: STDERR | STDOUT
        // 'fwrite'
        // 'vfprintf'
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
        return IdentityType::USAGE_INTERNAL_OUTPUT_CALL;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function getIdentifiers()
    {
        return self::$functions;
    }
}

/*EOF*/
