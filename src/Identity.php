<?php

namespace Potherca\Scanner;

class Identity
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var array */
    private $identities;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return array
     */
    final public function getIdentities()
    {
        return $this->identities;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(array $identities, $value)
    {
        $this->identities = $identities;
    }

    /**
     * @param $identity
     *
     * @return bool
     */
    final public function hasIdentity($identity)
    {
        return in_array($identity, $this->identities, true);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
