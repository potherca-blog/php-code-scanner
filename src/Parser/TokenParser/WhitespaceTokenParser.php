<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class WhitespaceTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            Token::T_WHITESPACE,
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        if (strpos($token[1], "\n") !== false) {
            $logger = $this->getLogger();

            $logger->log($logger->getCodeLine(), $logger->getTokenLine());

            $logger->resetTokenLine();
            $logger->resetCodeLine(sprintf('[%05s]: ', $token[2]));
        }
    }
}

/*EOF*/
