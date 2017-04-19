<?php

namespace Potherca\Scanner;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use Potherca\Scanner\Identifier\InternalFunctionsIdentifier;

class ScannerFactory
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var ArgumentInterface */
    private $arguments;

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(ArgumentInterface $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * @return Scanner
     */
    final public function create()
    {
        /* Grab variables */
        $blacklist = $this->arguments->getBlacklist();
        $directories = $this->arguments->getDirectories();
        $whitelist = $this->arguments->getWhitelist();

        $options = [
            'usedAttributes' => [
                'comments',      //  All comments that occurred between the previous non-discarded token and the current one. $node->getDocComment()
                // 'startFilePos', // Offset into the code string of the first character that is part of the node.
                // 'endFilePos', // Offset into the code string of the last character that is part of the node.
                'startLine',     // Line in which the node starts. $node->getLine()
                'endLine',       // Line in which the node ends.
                'endTokenPos',   // Offset into the token array of the last token in the node.
                'startTokenPos', // Offset into the token array of the first token in the node.
            ]
        ];

        /* Create simple classes (do not require other objects) */
        $parserFactory = new ParserFactory();
        $lexer = new Lexer($options);
        $identifier = $this->createIdentifier();

        /* Create more complex classes (require other objects) */
        $filesystems = $this->createFilesystems($directories);
        $parser = $this->createParser($parserFactory, $lexer);
        $visitor = new Visitor($identifier);
        $traverser = $this->createTraverser($visitor);

        /* Create the actual scanner */
        $scanner = new Scanner($filesystems, new Parser($parser, $lexer), $traverser);
        $scanner->setWhitelist($whitelist);
        $scanner->setBlacklist($blacklist);

        /* Return to sender */
        return $scanner;
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    private function createFilesystems(array $directories)
    {
        $filesystems = [];

        foreach ($directories as $directory) {
            $adapter = new Local($directory);
            $filesystems[] = new Filesystem($adapter);
        }

        return $filesystems;
    }

    private function createIdentifier()
    {
        /* Create Identifier */
        return new Identifier([
            /* @NOTE: The order Identifiers are registered in is of importance */
            new Identifier\DatabaseFunctionsIdentifier(),
            new Identifier\EmailFunctionsIdentifier(),
            new Identifier\EnvironmentFunctionsIdentifier(),
            new Identifier\FileFunctionsIdentifier(),
            new Identifier\InternalFunctionsIdentifier(InternalFunctionsIdentifier::VERSION_56),
            new Identifier\NetworkFunctionsIdentifier(),
            new Identifier\OutputIdentifier(),
        ]);
    }

    private function createParser(ParserFactory $parserFactory, Lexer $lexer)
    {
        return $parserFactory->create(ParserFactory::PREFER_PHP5, $lexer);
    }

    private function createTraverser(Visitor $visitor)
    {
        return new Traverser(
            new NodeTraverser(),
            [
                new NameResolver(null, ['preserveOriginalNames'=>true]),
                $visitor,
            ]
        );
    }
}

/*EOF*/
