<?php

namespace Potherca\Scanner\Identifier;

use PhpParser\Node;
use Potherca\Scanner\Identity;
use Potherca\Scanner\Node\NodeValueTrait;

abstract class AbstractSingleTypeIdentifier implements IdentifierInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    use NodeValueTrait;

    /** @var array */
    private $options;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @return array */
    abstract public function getIdentifiers();
    /** @return string */
    abstract public function getIdentityType();

    final public function getOptions()
    {
        return $this->options;
    }
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    final public function getOption($key)
    {
        $option = null;

        if (array_key_exists($key, $this->options) === true) {
            $option = $this->options[$key];
        }

        return $option;
    }

    /**
     * @param Node $node
     *
     * @return Identity
     *
     * @throws \Potherca\Scanner\Exception\NotYetImplementedException
     */
    final public function identify(Node $node)
    {
        $identification = Identity\IdentityType::NONE;

        $nodeType = $node->getType();

        $supportsNodeType = $this->supportsNodeType($nodeType);

        $value = $this->getValue($node);

        if ($supportsNodeType === true) {

            $identifiers = $this->getIdentifiers();

            $identified = in_array($value, $identifiers, false);

            if ($identified === true) {
                $identification = $this->getIdentityType();
            }
        }

        return new Identity([$identification], $value);
    }

    final public function supportsNodeType($tokenType)
    {
        return in_array($tokenType, $this->getSupportedNodeTypes(), true);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
