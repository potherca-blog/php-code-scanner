<?php

namespace Potherca\Scanner;

use PhpParser\Node;
use Potherca\Scanner\Identifier\IdentifierInterface;
use Potherca\Scanner\Identifier\IdentifierOption;
use Potherca\Scanner\Identifier\SupportsNodeTypeTrait;
use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeValue;

class Identifier implements IdentifierInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    use SupportsNodeTypeTrait;

    /** @var NodeValue */
    private $nodeValue;
    /** @var array */
    private $options;
    /** @var string[] */
    private $supportedNodeTypes;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function getSupportedIdentities()
    {
        $identities = [];

        $identifiers = $this->options[IdentifierOption::IDENTIFIERS];

        array_walk($identifiers, function (IdentifierInterface $identifier) use (&$identities) {
            $currentIdentities = $identifier->getSupportedIdentities();
            $identities = array_merge($identities, $currentIdentities);
        });

        $identities = array_unique($identities);

        return $identities;
    }

    public function getSupportedNodeTypes()
    {
        if ($this->supportedNodeTypes === null) {

            $supportedNodeTypes = [];

            $identifiers = $this->options[IdentifierOption::IDENTIFIERS];

            array_walk($identifiers, function (IdentifierInterface $identifier) use (&$supportedNodeTypes) {
                $currentIdentifiers = $identifier->getSupportedNodeTypes();
                $supportedNodeTypes = array_merge($supportedNodeTypes, $currentIdentifiers);
            });

            $this->supportedNodeTypes = array_unique($supportedNodeTypes);
        }

        return $this->supportedNodeTypes;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    final public function __construct(array $options, NodeValue $nodeValue)
    {
        $this->nodeValue = $nodeValue;
        $this->options = $options;
    }

    final public function identify(Node $node, array $options = [])
    {
        $identity = [];

        $identifiers = $this->options[IdentifierOption::IDENTIFIERS];

        array_walk($identifiers, function (IdentifierInterface $identifier) use ($node, &$identity, $options) {

            $nodeType = $node->getType();

            $supportsNodeType = $this->supportsNodeType($nodeType);

            if ($supportsNodeType === true) {
                $currentIdentity = $identifier->identify($node, $options);
                $identity = array_merge($identity, $currentIdentity->getIdentity());
            }
        });

        // Remove empty values
        $identity = array_filter($identity);

        if ($identity === []) {
            $identity = [IdentityType::UNKNOWN];
        }

        $value = $this->getValue($node);

        return new Identity($node, [
            'class' => $options['class'],
            'file' => $options['file'],
            'function' => $options['function'],
            'identity' => $identity,
            'namespace' => $options['namespace'],
            'value' => $value,
        ]);
    }

    final public function getValue($subject)
    {
        return $this->nodeValue->getValue($subject);
    }
}

/*EOF*/
