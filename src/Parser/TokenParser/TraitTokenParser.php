<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class TraitTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
        /*
            Token::T_TRAIT,
            Token::T_TRAIT_C,
            Token::T_INSTEADOF,
        */
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        /*/ @TODO: Support traits./*/
    }
}

/*EOF*/
