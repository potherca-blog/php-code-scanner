<?php

namespace Potherca\Scanner\Visitor;

use PhpParser\Node;
use Potherca\Scanner\Exception\RuntimeException;
use Potherca\Scanner\Identifier;
use Potherca\Scanner\Identity;

interface VisitorInterface
{
    /** @return Identity */
    public function getIdentities();

    /**
     * @param array $nodes
     * @param Node $node
     *
     * @return Node
     *
     * @throws RuntimeException
     */
    public function visit(array $nodes, Node $node);

    public function storeIdentity(Node $node, Identity $identities);
}

/*EOF*/
