<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class StringTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            Token::T_CONSTANT_ENCAPSED_STRING,
            Token::T_START_HEREDOC,
            Token::T_END_HEREDOC,
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        /* @FIXME: Because the SQL query is split across string (because of interjected `mysql_quote`, etc.) we need to keep track of string to concat.
         *         We also need to keep track for HEREDOC/NOWDOC
         *
         * $parser = new Parser($token[1]);
         * if (count($parser->statements) > 0) {
         * $flags = Query::getFlags($parser->statements[0]);
         * }
         */
    }
}

/*EOF*/
