<?php

namespace Potherca\Scanner;

use PhpParser\Node;
use PhpParser\NodeTraverserInterface;
use PhpParser\NodeVisitor as NodeVisitorInterface;

class Traverser implements NodeTraverserInterface
{
    /** @var NodeTraverserInterface */
    private $traverser;
    /** @var NodeVisitorInterface[] */
    private $visitors;

    /** @return NodeVisitorInterface[] */
    final public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * @param NodeTraverserInterface $traverser
     * @param NodeVisitorInterface[] $visitors
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

        array_walk($visitors, function (NodeVisitorInterface $visitor) use (&$traverser) {
            $traverser->addVisitor($visitor);
        });

        $traverser->traverse($nodes);

        $identities = [];

        array_walk($visitors, function (NodeVisitorInterface $visitor) use (&$identities) {
            if ($visitor instanceof Visitor) {
                $currentIdentities = $visitor->getIdentities();
                $identities = array_merge($identities, $currentIdentities);
            }
        });

        return $identities;
    }

    /**
     * Adds a visitor.
     *
     * @param NodeVisitorInterface $visitor Visitor to add
     */
    public function addVisitor(NodeVisitorInterface $visitor)
    {
        $this->visitors[] = $visitor;
    }

    /**
     * Removes an added visitor.
     *
     * @param NodeVisitorInterface $visitor
     */
    public function removeVisitor(NodeVisitorInterface $visitor)
    {
        $found = false;

        array_walk($this->visitors, function ($storedVisitor, $index) use (&$found, $visitor) {
            if ($found === false && $storedVisitor === $visitor) {
                unset($this->visitors[$index]);
                $found = true;
            }
        });
    }
}

/*EOF*/
