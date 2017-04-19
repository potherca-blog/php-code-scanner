<?php

namespace Potherca\Scanner;

use PhpParser\Node;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor;

class Traverser implements NodeTraverserInterface
{
    /** @var NodeTraverserInterface */
    private $traverser;
    /** @var NodeVisitor[] */
    private $visitors = [];

    /** @return NodeVisitor[] */
    final public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * @param NodeTraverserInterface $traverser
     * @param NodeVisitor[] $visitors
     */
    final public function __construct(NodeTraverserInterface $traverser, array $visitors)
    {
        $this->traverser = $traverser;

        $this->visitors = $visitors;
    }

    /**
     * Traverses an array of nodes using the registered visitors.
     *
     * @param Node[] $nodes Array of nodes
     *
     * @return Node[] Traversed array of nodes
     */
    final public function traverse(array $nodes)
    {
        $traverser = $this->traverser;
        $visitors = $this->visitors;

        array_walk($visitors, function (NodeVisitor $visitor) use (&$traverser) {
            $traverser->addVisitor($visitor);
        });

        $traverser->traverse($nodes);

        $identities = [];

        array_walk($visitors, function (NodeVisitor $visitor) use (&$identities) {
            if ($visitor instanceof Visitor) {
                $currentIdentities = $visitor->getIdentities();
                array_merge($identities, $currentIdentities);
            }
        });

        return $identities;
    }

    /**
     * Adds a visitor.
     *
     * @param NodeVisitor $visitor Visitor to add
     */
    public function addVisitor(NodeVisitor $visitor)
    {
        // @TODO: Implement addVisitor() method.
    }

    /**
     * Removes an added visitor.
     *
     * @param NodeVisitor $visitor
     */
    public function removeVisitor(NodeVisitor $visitor)
    {
        // @TODO: Implement removeVisitor() method.
    }
}

/*EOF*/
