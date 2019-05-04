<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class FunctionTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            Token::T_FUNCTION, // 'function'
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        /*/ function declaration /*/
        $functionName = $tokens[$index + 2];
        $this->result['declared'][] = $functionName[1];
    }
}

/*EOF*/
