<?php

namespace Potherca\Scanner\Parser\TokenParser;

use Potherca\Scanner\Parser\Token;

class KeywordTokenParser extends AbstractTokenParser
{
    public function getSupportedTokens()
    {
        return [
            Token::T_STRING,
        ];
    }

    public function parseToken(array $tokens, $index, $token)
    {
        /*/ potential function usage /*/
        $candidate = $token[1];

        if (in_array(strtolower($candidate), ['false', 'true', 'null'], true)) {
            $this->ignore();
        } elseif ($this->provider->isFileFunction($candidate)) {
            $this->result->addInternalFilesystemCall($candidate);
        } elseif ($this->provider->isDatabaseFunction($candidate)) {
            $this->result->addInternalDatabaseCall($candidate);
        } elseif ($this->provider->isInternalFunction($candidate)) {
            $this->result->addInternalCall($candidate);
            echo $token[1] . PHP_EOL;
        } else {

            /*
             * How to find out what is a class?
             * How to find out what is a function?
             * How to find out what is a class method?
             */
            $this->result->addUserlandCall($candidate);
        }
        unset($candidate);
    }
}

/*EOF*/
