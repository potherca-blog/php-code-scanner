<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class VariableTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            Token::T_VARIABLE,
            Token::T_STRING_VARNAME,
            Token::T_ENCAPSED_AND_WHITESPACE,
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        /*/ @TODO: Variables  /*/
        /* @FIXME: What to do about $GLOBALS | $_SERVER | $_GET | $_POST | $_FILES | $_COOKIE | $_SESSION | $_REQUEST | $_ENV  ??? */
    }
}

/*EOF*/
