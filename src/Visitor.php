<?php

namespace Potherca\Scanner;

use PhpParser\Node;
use PhpParser\NodeVisitor;
use Potherca\Scanner\Exception\NotYetImplementedException;
use Potherca\Scanner\Exception\RuntimeException;
use Potherca\Scanner\Node\NodeValueTrait;
use Potherca\Scanner\Visitor\VisitorInterface;

class Visitor implements VisitorInterface, NodeVisitor
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    use NodeValueTrait;

    /** @var */
    private $tree = [];
    /** @var Identifier */
    private $identifier;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @param mixed $tree */
    final public function setTree(array $tree)
    {
        $this->tree = $tree;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(Identifier $identifier)
    {
        $this->identifier = $identifier;
    }

    /* Methods of the PhpParser\NodeVisitor interface.
     *
     * All four methods can either return the changed node or not return at all
     * (i.e. null) in which case the current node is not changed.
     */

    /**
     * Called once after traversal.
     *
     * Return value semantics:
     *  - null:      $nodes stays as-is
     *  - otherwise: $nodes is set to the return value
     *
     * @param Node[] $nodes Array of nodes
     *
     * @return null|Node[] Array of nodes
     */
    public function afterTraverse(array $nodes)
    {
        return null;
    }

    /**
     * Called once before traversal.
     *
     * Return value semantics:
     *  - null:      $nodes stays as-is
     *  - otherwise: $nodes is set to the return value
     *
     * @param Node[] $nodes Array of nodes
     *
     * @return null|Node[] Array of nodes
     */
    public function beforeTraverse(array $nodes)
    {
        return null;
    }

    /**
     * Called when entering a node.
     *
     * Return value semantics:
     *  - null
     *        => $node stays as-is
     *  - NodeTraverser::DONT_TRAVERSE_CHILDREN
     *        => Children of $node are not traversed. $node stays as-is
     *  - NodeTraverser::STOP_TRAVERSAL
     *        => Traversal is aborted. $node stays as-is
     *  - otherwise
     *        => $node is set to the return value
     *
     * @param Node $node Node
     *
     * @return null|int|Node Node
     */
    public function enterNode(Node $node)
    {
        $this->log($node, '>');

        return null;
    }

    /**
     * Called when leaving a node.
     *
     * Return value semantics:
     *  - null
     *        => $node stays as-is
     *  - NodeTraverser::REMOVE_NODE
     *        => $node is removed from the parent array
     *  - NodeTraverser::STOP_TRAVERSAL
     *        => Traversal is aborted. $node stays as-is
     *  - array (of Nodes)
     *        => The return value is merged into the parent array (at the position of the $node)
     *  - otherwise
     *        => $node is set to the return value
     *
     * @param Node $node Node
     *
     * @return Node Node
     *
     * @throws RuntimeException
     */
    public function leaveNode(Node $node)
    {
        $this->log($node, '<');

        $this->visit($this->tree, $node);

        return null;
    }

    /* Methods of the Potherca\Scanner\Visitor\VisitorInterface. */
    final public function getIdentities()
    {
        return [new NotYetImplementedException('// @FIXME: Retrieve identified nodes from **somewhere**')];
    }
    /**
     * @param Node $node
     * @param Identity $identities
     */
    final public function storeIdentity(Node $node, Identity $identities)
    {
        //
        // @FIXME: Store $identity **SOMEWHERE**
    }

    final public function visit(array $nodes, Node $node)
    {
        $identity = $this->identifier->identify($node);

        /*
            $parser = new PHPSQLParser();
            $parsed = $parser->parse($sql, true);
            print_r($parsed);
        */
        if (
            $node instanceof Node\Stmt\Namespace_
            || $node instanceof Node\Stmt\Class_
            || $node instanceof Node\Stmt\ClassMethod
            || $node instanceof Node\Stmt\Function_
        ) {
            $type = '>';
            $this->log($node, $type);
        }

        if ($identity->hasIdentity(Identity\IdentityType::UNKNOWN) === false) {
            $type = $identity;
            $this->log($node, $type);
        }

        $this->storeIdentity($node, $identity);
    }
}

/*EOF*/
