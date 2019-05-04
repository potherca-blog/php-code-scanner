<?php

namespace Potherca\Scanner\Parser\TokenParser;

interface TokenParserInterface
{
    /**
     * @return array
     */
    public function getSupportedTokens();

    /**
     * @param array $tokens
     * @param int $index
     * @param string $token
     *
     * @return mixed
     */
    public function parseToken(array $tokens, $index, $token);

    /**
     * @param $tokenName
     *
     * @return bool
     */
    public function supportsTokens($tokenName);
}

/*EOF*/
