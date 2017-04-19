<?php

namespace Potherca\Scanner\Identifier;

use PhpParser\Node;
use Potherca\Scanner\Identity;

interface IdentifierInterface/* extends SupportsNodeInterface*/
{
    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * Provides a list of all the NodeTypes the identifier identifies
     *
     * @return array
     */
    public function getSupportedNodeTypes();

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * Create am Identifier object
     *
     * The constructor is passed an array of options. It is each Identifier's
     * own responsibility to retrieve relevant options from the given array.
     *
     * @param array $options
     */
    public function __construct(array $options);

    /**
     * @param Node $node The Node to identify
     *
     * @return Identity The identity of the given node
     */
    public function identify(Node $node);
}

/*EOF*/
