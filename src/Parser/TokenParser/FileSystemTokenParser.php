<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class FileSystemTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            Token::T_DIR,
            Token::T_FILE,
            Token::T_INCLUDE,
            Token::T_INCLUDE_ONCE,
            Token::T_REQUIRE,
            Token::T_REQUIRE_ONCE,
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        /*/ @TODO: File References /*/
    }
}

/*EOF*/
