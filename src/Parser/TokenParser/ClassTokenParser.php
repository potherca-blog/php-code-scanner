<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class ClassTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            /* Declaration */
            Token::T_CLASS, // 'class'
            Token::T_EXTENDS, /// 'extends'

            /* Usage */
            Token::T_NEW,
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        /*/ Class declaration /*/
        /*/ Class usage /*/
    }
}

/*EOF*/
