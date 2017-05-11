<?php

namespace Potherca\Scanner\Identifier;

use PhpParser\Node;
use Potherca\Scanner\Identity;
use Potherca\Scanner\Node\NodeValue;

abstract class AbstractSingleTypeIdentifier implements IdentifierInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    use SupportsNodeTypeTrait;

    /** @var NodeValue */
    private $nodeValue;
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

    final public function getSupportedIdentities()
    {
        return [$this->getIdentityType()];
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(array $options, NodeValue $nodeValue)
    {
        $this->nodeValue = $nodeValue;
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
     * @param array $options
     *
     * @return Identity
     */
    final public function identify(Node $node, array $options = [])
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

        return new Identity($node, [
            'class' => $options['class'],
            'file' => $options['file'],
            'function' => $options['function'],
            'identity' => [$identification],
            'namespace' => $options['namespace'],
            'value' => $value,
        ]);
    }

    final public function getValue($subject)
    {
        return $this->nodeValue->getValue($subject);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
