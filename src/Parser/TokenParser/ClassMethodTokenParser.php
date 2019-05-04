<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class ClassMethodTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            Token::T_DOUBLE_COLON, // '::'
            Token::T_OBJECT_OPERATOR, // '->'
            Token::T_PAAMAYIM_NEKUDOTAYIM, // '::'
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        /*/ Class method usage /*/
    }
}

/*EOF*/
