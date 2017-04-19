<?php

namespace Potherca\Scanner;

use PhpParser\Lexer;
use PhpParser\Node;
use PhpParser\Parser as PhpParser;
use PhpParser\Error;
use Potherca\Scanner\Exception\ParserException;

class Parser
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var Lexer */
    private $lexer;
    /** @var PhpParser */
    private $parser;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @return Lexer */
    public function getLexer()
    {
        return $this->lexer;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param PhpParser $parser
     * @param Lexer $lexer
     */
    public function __construct(PhpParser $parser, Lexer $lexer)
    {
        $this->parser = $parser;
        $this->lexer = $lexer;
    }
    /**
     * @param string $content
     *
     * @return Node[]
     *
     * @throws ParserException
     */
    final public function parse($content)
    {
        $parser = $this->parser;

        try {
            $tree = $parser->parse($content);

            if ($tree === null) {
                $tree = [];
            }
        } catch (Error $error) {
            throw new ParserException($error->getMessage(), ParserException::PHP_PARSE_ERROR, $error);
        }

        return $tree;
    }
}

/*EOF*/
