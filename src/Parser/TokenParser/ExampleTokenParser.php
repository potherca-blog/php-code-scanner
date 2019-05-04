<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class ExampleTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            Token::,
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {

    }
}

/*EOF*/
