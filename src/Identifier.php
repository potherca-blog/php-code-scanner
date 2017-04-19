<?php

namespace Potherca\Scanner;

use PhpParser\Node;
use Potherca\Scanner\Identifier\IdentifierInterface;
use Potherca\Scanner\Identifier\IdentifierOption;
use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeValueTrait;

class Identifier implements IdentifierInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    use NodeValueTrait;

    /** @var array */
    private $options;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    public function getSupportedNodeTypes()
    {
        $supportedNodeTypes = [];

        $identifiers = $this->options[IdentifierOption::IDENTIFIERS];

        array_walk($identifiers, function (IdentifierInterface $identifier) use (&$supportedNodeTypes) {
            $currentIdentifiers = $identifier->getSupportedNodeTypes();
            $supportedNodeTypes = array_merge($supportedNodeTypes, $currentIdentifiers);
        });

        return $supportedNodeTypes;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    final public function __construct(array $options)
    {
        $this->options = $options;
    }

    final public function identify(Node $node)
    {
        $identities = [];

        $identifiers = $this->options[IdentifierOption::IDENTIFIERS];

        array_walk($identifiers, function (IdentifierInterface $identifier) use ($node, &$identities) {
            $currentIdentities = $identifier->identify($node);
            $identities = array_merge($identities, $currentIdentities->getIdentities());
        });

        // Remove empty values
        $identities = array_filter($identities);

        if ($identities === []) {
            $identities = [IdentityType::UNKNOWN];
        }

        $value = $this->getValue($node);

        return new Identity($identities, $value);
    }
}

/*EOF*/
