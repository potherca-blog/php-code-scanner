<?php

namespace Potherca\Scanner;

use PhpParser\Node;
use Potherca\Scanner\Identifier\IdentifierInterface;
use Potherca\Scanner\Identifier\IdentifierOption;
use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeValue;

class Identifier implements IdentifierInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var NodeValue */
    private $nodeValue;
    /** @var array */
    private $options;
    /** @var string[] */
    private $supportedNodeTypes;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
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

    final public function identify(Node $node)
    {
        $identities = [];

        // $supportedNodeTypes = $this->getSupportedNodeTypes();

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

    final public function getValue($subject)
    {
        return $this->nodeValue->getValue($subject);
    }
}

/*EOF*/
