<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Logger;
use Potherca\Scanner\Result;
use Potherca\Scanner\Provider;

abstract class AbstractTokenParser implements TokenParserInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var Logger */
    private $logger;
    /** @var Result */
    private $result;
    /** @var Provider */
    private $provider;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @return Logger */
    public function getLogger()
    {
        return $this->logger;
    }

    /** @return Result */
    public function getResult()
    {
        return $this->result;
    }

    /** @return Provider */
    public function getProvider()
    {
        return $this->provider;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(Result $result, Provider $provider, Logger $logger)
    {
        $this->logger = $logger;
        $this->provider = $provider;
        $this->result = $result;
    }

    final public function supportsTokens($tokenName)
    {
        return in_array($tokenName, $this->getSupportedTokens(), true);
    }

    /**
     * Empty placeholder function to make code more readable when ignoring tokens
     */
    final public function ignore() {}

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
