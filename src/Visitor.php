<?php

namespace Potherca\Scanner;

use PhpParser\Node;
use PhpParser\NodeVisitor as NodeVisitorInterface;
use Potherca\Scanner\Exception\NotYetImplementedException;
use Potherca\Scanner\Exception\RuntimeException;
use Potherca\Scanner\Visitor\VisitorInterface;

class Visitor implements VisitorInterface, NodeVisitorInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //@FIXME: Place "location" properties (file, namespace, class, function) in separate Object
    /** @var string */
    private $filename;
    /** @var string */
    private $namespace = '';
    /** @var string */
    private $class = '';
    /** @var string */
    private $function = '';

    /** @var Identity[] */
    private $identities = [];
    /** @var Identifier */
    private $identifier;
    /** @var array*/
    private $tree = [];
    /** @var array */
    private $supportedNodeTypes = [];

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @param mixed $tree */
    final public function setTree(array $tree)
    {
        $this->tree = $tree;
    }

    /** @param string $filename */
    final public function setFilename($filename)
    {
        $this->filename = (string) $filename;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(Identifier $identifier)
    {
        $this->identifier = $identifier;
    }

    /////////////////////////// PhpParser\NodeVisitor \\\\\\\\\\\\\\\\\\\\\\\\\\
    /*
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
    final public function afterTraverse(array $nodes)
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
    final public function beforeTraverse(array $nodes)
    {
        $this->reset();

        $this->supportedNodeTypes = $this->identifier->getSupportedNodeTypes();

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
    final public function enterNode(Node $node)
    {
        if (
            $node instanceof Node\Stmt\Namespace_
            || $node instanceof Node\Stmt\Class_
            || $node instanceof Node\Stmt\ClassMethod
            || $node instanceof Node\Stmt\Function_
        ) {
            $this->log($node, '>');

            $propertyName = $this->getPropertyNameForNode($node);

            $this->{$propertyName} = (string) $node->name;
        }

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
    final public function leaveNode(Node $node)
    {
        if (
            $node instanceof Node\Stmt\Namespace_
            || $node instanceof Node\Stmt\Class_
            || $node instanceof Node\Stmt\ClassMethod
            || $node instanceof Node\Stmt\Function_
        ) {
                $this->log($node, '<');
        }

        $this->visit($this->tree, $node);

        if (
            $node instanceof Node\Stmt\Namespace_
            || $node instanceof Node\Stmt\Class_
            || $node instanceof Node\Stmt\ClassMethod
            || $node instanceof Node\Stmt\Function_
        ) {
            $propertyName = $this->getPropertyNameForNode($node);
            $this->{$propertyName} = '';
        }

        return null;
    }

    ////////////////////////////// VisitorInterface \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function getIdentities()
    {
        return $this->identities;
    }

    /**
     * @return array
     */
    final public function getSupportedIdentities()
    {
        return $this->identifier->getSupportedIdentities();
    }

    /**
     * @param Node $node
     * @param Identity $identity
     */
    final public function storeIdentity(Node $node, Identity $identity)
    {
        $this->identities[] = $identity;
    }

    final public function visit(array $nodes, Node $node)
    {
        if (
            in_array($node->getType(), $this->supportedNodeTypes, true) === true
            || in_array(get_class($node), $this->supportedNodeTypes, true) === true
        ) {
            $properties = [
                'class' => $this->class,
                'file' => $this->filename,
                'function' => $this->function,
                'namespace' => $this->namespace,
            ];

            $identity = $this->identifier->identify($node, $properties);

            /*
                $parser = new PHPSQLParser();
                $parsed = $parser->parse($sql, true);
                print_r($parsed);
            */
            if ($identity->hasIdentity(Identity\IdentityType::UNKNOWN) === false) {
                $this->log($node, $identity);
                $this->storeIdentity($node, $identity);
            }
        }

    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @noinspection MoreThanThreeArgumentsInspection
     *
     * @param Node $node
     * @param string|Identity $type
     *
     * @TODO: Replace with Monolog + Add 'DEBUG' status
     */
    private function log(Node $node, $type)
    {
        if ($node instanceof Node\Stmt\Nop === false && $type !== ' ') {

            // @NOTE: Uncomment the $indicator and $status to get more output
            // $indicator = '      ';
            // $status = '';
            $messageFormat = ' %s [%s:%s] %s %s = "%s"';

            if($type instanceof Identity) {
                $identities = $type->getIdentity();

                $hasUnknownIdentity = $type->hasIdentity(Identity\IdentityType::UNKNOWN);
                if ($hasUnknownIdentity === false) {
                    $indicator = '----->';
                    $status = implode(', ', $identities);
                }
            }elseif (
                $node instanceof Node\Stmt\Namespace_
                || $node instanceof Node\Stmt\Class_
                || $node instanceof Node\Stmt\ClassMethod
                || $node instanceof Node\Stmt\Function_
            ) {
                switch ($type) {
                    case '<':
                        $status = 'Leaving';
                        $indicator = '<=====';
                        break;
                    case '>':
                        $status = 'Entering';
                        $indicator = '=====>';
                        break;

                    default:
                        break;
                }
            }

            if (isset($status, $indicator)) {
                try {
                    $value = $this->identifier->getValue($node);
                } catch (NotYetImplementedException $exception) {
                    $value = $exception->getMessage();
                }

                $messageContent = [
                    $indicator,
                    $node->getAttribute('startLine'),
                    $node->getAttribute('endLine'),
                    $status,
                    $node->getType(),
                    $value,
                ];

                vprintf($messageFormat.PHP_EOL, $messageContent);
            }
        }
    }

    private function reset()
    {
        $this->class = '';
        $this->function = '';
        $this->identities = [];
        $this->namespace = '';
        $this->tree = [];
        // $this->filename = '';

    }

    /**
     * @param Node $node
     *
     * @return string
     */
    private function getPropertyNameForNode(Node $node)
    {
        $propertyMap = [
            Node\Stmt\Namespace_::class => 'namespace',
            Node\Stmt\Class_::class => 'class',
            Node\Stmt\ClassMethod::class => 'function',
            Node\Stmt\Function_::class => 'function',

        ];

        $className = get_class($node);

        return $propertyMap[$className];
    }
}

/*EOF*/
