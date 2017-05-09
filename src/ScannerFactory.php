<?php

namespace Potherca\Scanner;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use Potherca\Scanner\Identifier\IdentifierInterface;
use Potherca\Scanner\Identifier\IdentifierOption;
use Potherca\Scanner\Node\NodeValue;

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
        $arguments = $this->arguments;

        $options = [
            IdentifierOption::PHP_VERSION => $arguments->getPhpVersion()
        ];

        /* Grab variables */
        $blacklist = $arguments->getBlacklist();
        $directories = $arguments->getDirectories();
        $whitelist = $arguments->getWhitelist();

        $lexerOptions = [
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
        $lexer = new Lexer($lexerOptions);
        $nodeValue = new NodeValue();

        /* Create more complex classes (require other objects) */
        $identifier = $this->createIdentifier($options, $nodeValue);
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

    private function createIdentifier($options, NodeValue $nodeValue)
    {
        $arguments = $this->arguments;

        $identifiers = $arguments->getIdentifiers();

        $existingClasses = get_declared_classes();

        array_walk($identifiers, function ($path){
            /** @noinspection PhpIncludeInspection */
            require_once $path;
        });

        $declaredClasses = get_declared_classes();

        $classes = array_diff($declaredClasses, $existingClasses);

        $identifiers = [];

        array_walk($classes, function ($className) use (&$identifiers, $options, $nodeValue) {
            if (is_subclass_of($className, IdentifierInterface::class) === true) {
                $identifiers[] = new $className($options, $nodeValue);
            }
        });

        $options[IdentifierOption::IDENTIFIERS] = $identifiers;

        return new Identifier($options, $nodeValue);
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
                /* @NOTE: The order Visitors are registered in is of importance */
                new NameResolver(null, ['preserveOriginalNames'=>true]),
                $visitor,
            ]
        );
    }
}

/*EOF*/
