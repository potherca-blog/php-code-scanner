<?php

namespace Potherca\Scanner\Node;

use PhpParser\Node;
use Potherca\Scanner\Exception\NotYetImplementedException;
use Potherca\Scanner\Identity;

trait NodeValueTrait
{
    /**
     * @param \stdClass|resource|Node|array|string|int|bool|null $subject
     * @param string $value
     *
     * @return string
     *
     * @throws NotYetImplementedException
     */
    final public function getValue($subject, $value = '')
    {
        if (is_scalar($subject)) {
            $value .= $this->getValueFromScalar($subject, $value);
        } elseif (is_array($subject)) {
            $value .= $this->getValueFromArray($subject, $value);
        } elseif ($subject instanceof Node) {
            $value .= $this->getValueFromNode($subject, $value);
        } else {
            throw $this->createException($subject);
        }

        return $value;
    }

    /** @noinspection MoreThanThreeArgumentsInspection
     *
     * @param Node $node
     * @param string|Identity $type
     *
     * @TODO: Replace with Monolog + Add 'DEBUG' status
     */
    final public function log(Node $node, $type)
    {
        if ($node instanceof Node\Stmt\Nop === false && $type !== ' ') {

            // @NOTE: Uncomment the $indicator and $status to get more output
            // $indicator = '      ';
            // $status = '';
            $messageFormat = ' %s [%s:%s] %s %s = "%s"';

            if($type instanceof Identity) {
                if ($type->hasIdentity(Identity\IdentityType::UNKNOWN) === false) {
                    $identities = $type->getIdentities();

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
                    $value = $this->getValue($node);
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

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param array $subject
     * @param string $value
     *
     * @return string
     *
     * @throws \Potherca\Scanner\Exception\NotYetImplementedException
     */
    private function getValueFromArray(array $subject, $value)
    {
        $values = [];

        array_walk($subject, function (Node $argument) use (&$values) {
            $values[] = $this->getValue($argument);
        });

        $value .= implode(', ', $values);

        return $value;
    }

    /**
     * @param bool|null|int|float $subject
     * @param string $value
     *
     * @return string
     */
    private function getValueFromScalar($subject, $value)
    {
        switch ($subject) {

            case $subject === null:
                $value .= 'NULL';
                break;

            case $subject === true:
                $value .= 'TRUE';
                break;

            case $subject === false:
                $value .= 'FALSE';
                break;

            default:
                $value .= (string) $subject;
                break;

        }

        return $value;
    }

    private function nodeHasProperty(Node $node, $property)
    {
        $subNodeNames = $node->getSubNodeNames();

        return in_array($property, $subNodeNames, true);
    }

    /**
     * @param Node $node
     * @param string $value
     *
     * @return string
     * @throws NotYetImplementedException
     */
    private function getValueFromNode(Node $node, $value)
    {
        // @TODO: This code needs to be cleaned up and made more generic.

        $subNodeNames = $node->getSubNodeNames();

        $supportedNodeNames = ['items', 'left', 'name', 'right', 'value', 'var'];

        if ($subNodeNames === ['name']) {
            /** @noinspection PhpUndefinedFieldInspection */
            $value .= sprintf('$%s', $node->name);
        } elseif ($this->nodeHasProperty($node, 'consts')) {
            $value .= $this->getValue($node->consts);
        } elseif ($node instanceof Node\Name) {
            $value .= $node->toString();
        } elseif (
            $node instanceof Node\Expr\FuncCall
            || $node instanceof Node\Expr\AssignOp
            || $node instanceof Node\Expr\BinaryOp
        ) {
            if ($this->nodeHasProperty($node, 'name')) {
                /** @noinspection PhpUndefinedFieldInspection */
                $value .= $this->getValue($node->name, $value);
            }

            if ($this->nodeHasProperty($node, 'left')) {
                /** @noinspection PhpUndefinedFieldInspection */
                $value .= $this->getValue($node->left, $value);
            } elseif ($this->nodeHasProperty($node, 'right')) {
                /** @noinspection PhpUndefinedFieldInspection */
                $value .= $this->getValue($node->right, $value);
            }
        } elseif ($node instanceof Node\Scalar\MagicConst) {
            $value .= $node->getName();
        } elseif ($node instanceof Node\Param) {

            $default = '';

            if ($this->nodeHasProperty($node, 'default')) {
                $default = $node->default;

                if ($default instanceof Node) {
                    $default = $default->getType();
                }

                $default = '"' . $default . '"';
            }


            $value .= vsprintf('%s%s$%s%s', [
                $node->type ? $node->type . ' ' : '',
                $node->byRef ? '&' : '',
                $node->name,
                $node->variadic ? '...' : '',
                $default
            ]);
        } elseif ($this->nodeHasProperty($node, 'value')) {
            /** @noinspection PhpUndefinedFieldInspection *///
            $nodeValue = $node->value;

            if (is_scalar($nodeValue)) {
                $value .= $nodeValue;
            } else {
                $value .= $this->getValue($nodeValue, $value);
            }
        } elseif ($this->nodeHasProperty($node, 'items')) {
            $value .= sprintf('(%s items)', $node->items);
        } elseif ($this->nodeHasProperty($node, 'var')) {
            /** @noinspection PhpUndefinedFieldInspection */
            $var = $node->var;

            if (is_string($var)) {
                $value .= sprintf('$%s', $var);
            } else {
                $value .= sprintf('$%s', $var->name);
            }

            if (
                $this->nodeHasProperty($node, 'byref')
                /** @noinspection PhpUndefinedFieldInspection */
                && $node->byref === true
            ) {
                $value .= '&' . $value;
            }

            // @FIXME: All of these are NOT handled but might occur: name, expr, types, args, stmts, dim
        } elseif (
            $node instanceof Node\Stmt\Echo_
        ) {
            $value .= $this->getValue($node->exprs);
        } elseif (
            $node instanceof Node\Expr\Closure
            || $node instanceof Node\Stmt\Function_
        ) {
            $name = '';
            $parameters = [];
            $static = '';
            $use = '';

            if ($this->nodeHasProperty($node, 'static') && $node->static === true) {
                $static = 'static ';
            }

            if ($this->nodeHasProperty($node, 'name')) {
                $name = $this->getValue($node->name);
            }

            /** @var Node\Param[] $params */
            $params = $node->params;
            foreach ($params as $param) {
                $parameters[] = $this->getValue($param);
            }

            /** @var Node\Expr\ClosureUse[] $params */
            if ($this->nodeHasProperty($node, 'uses')) {
                $uses = $node->uses;
                if (count($uses) > 0) {
                    $use = [];
                    foreach ($uses as $useNode) {
                        $use[] = $this->getValue($useNode);
                    }
                    $use = sprintf('use (%s) ', implode(', ', $use));
                }
            }

            $value .= vsprintf(
                '%sfunction %s(%s) %s{/*...*/}',
                [
                    $static,
                    $name,
                    implode(', ', $parameters),
                    $use,
                ]
            );

        } elseif (
            $node instanceof Node\Stmt\Namespace_
        ) {
            /*
             * namespace \Example\ClassName;
             * namespace \Example\ClassName, \Example\AnotherClassName;
             * namespace \Example;
             *
             * So either the $name contains a value OR $stmts contains values (which in turn contain names)?
             * Or does $stmts always contain (at least) _one_ name?
             */
        } elseif (
            $node instanceof Node\Stmt\Class_
            || $node instanceof Node\Stmt\Interface_
            || $node instanceof Node\Stmt\ClassMethod
            || $node instanceof Node\Stmt\Property
            || $node instanceof Node\Stmt\PropertyProperty
        ) {
            // @FIXME: Add Class support (Class, Method, Property)

            if ($this->nodeHasProperty($node, 'name')) {
                /** @noinspection PhpUndefinedFieldInspection */
                $value .= $this->getValue($node->name, $value);
            }

            // @NOTE: $flags contains: public | protected | private | static | abstract | final | etc.

            // Class_            => "name", "stmts", "flags", "extends", "implements"
            // ClassMethod       => "name", "stmts", "flags", "byRef", "params", "returnType"
            // Property          =>                  "flags", "props"
            // PropertyProperty  => "name", "default"
            // Interface_        => "name", "stmts", "extends"
        } elseif (
            $node instanceof Node\Stmt\Nop
            || $node instanceof Node\Stmt\Return_
            || $node instanceof Node\Stmt\If_
            || $node instanceof Node\Expr\Isset_
        ) {
            // Ignore
        } else {

            $message = '// @FIXME: Use another property from "%s" (it does does not have one of "%s"). Available properties: "%s"';

            $errorMessage = vsprintf(
                $message,
                [
                    get_class($node),
                    implode('", "', $supportedNodeNames),
                    implode('", "', $subNodeNames),
                ]
            );

            throw new NotYetImplementedException($errorMessage);
        }

        return $value;
    }

    /**
     * @param $subject
     *
     * @return NotYetImplementedException
     */
    private function createException($subject)
    {
        $messageProperties = ['unknown', gettype($subject)];

        if (is_object($subject)) {
            $messageProperties = ['object', get_class($subject)];
        } elseif (is_resource($subject)) {
            $messageProperties = ['resource', get_resource_type($subject)];
        }

        $errorMessage = vsprintf('Getting value from "%s" %s is not (yet) supported', $messageProperties);

        return new NotYetImplementedException($errorMessage);
    }
}

/*EOF*/
