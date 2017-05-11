<?php

namespace Potherca\Scanner\Visitor;

use PhpParser\Node;
use Potherca\Scanner\Exception\RuntimeException;
use Potherca\Scanner\Identity;

interface VisitorInterface
{
    /** @return Identity */
    public function getIdentities();

    /** string[] */
    public function getSupportedIdentities();
    /**
     * @param array $nodes
     * @param Node $node
     *
     * @return Node
     *
     * @throws RuntimeException
     */
    public function visit(array $nodes, Node $node);

    /**
     * @param Node $node
     * @param Identity $identity
     */
    public function storeIdentity(Node $node, Identity $identity);

}

/*EOF*/
