<?php

namespace Potherca\Scanner;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PhpParser\NodeVisitor as NodeVisitorInterface;
use Potherca\Scanner\Exception\ParserException;
use Potherca\Scanner\Visitor\VisitorInterface;

class Scanner
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var array */
    private $blacklist = [];
    /** @var Filesystem[] */
    private $filesystems;
    /** @var Parser */
    private $parser;
    /** @var array */
    private $result = [];
    /** @var Traverser */
    private $traverser;
    /** @var array */
    private $whitelist = [];

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @return array */
    final public function  getResult()
    {
        return $this->result;
    }

    final public function setBlacklist(array $blacklist)
    {
        $this->blacklist = $blacklist;
    }

    final public function setWhitelist(array $whitelist)
    {
        $this->whitelist = $whitelist;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * Scanner constructor.
     *
     * @param Filesystem [] $filesystems
     * @param Parser $parser
     * @param Traverser $traverser
     */
    final public function __construct(array $filesystems, Parser $parser, Traverser $traverser)
    {
        $this->filesystems = $filesystems;
        $this->parser = $parser;
        $this->traverser = $traverser;
    }

    final public function scan()
    {
        $filesystems = $this->filesystems;

        foreach ($filesystems as $filesystem) {

            /** @var Local $adapter */
            $adapter = $filesystem->getAdapter();
            $pathPrefix = $adapter->getPathPrefix();
            echo '================================================================' . PHP_EOL;
            vprintf(' =====> Entering folder "%s"%s', [$pathPrefix, PHP_EOL]);

            $this->scanFileSystem($filesystem);

            echo '----------------------------------------------------------------' . PHP_EOL;
            echo ' <==== Leaving folder'.PHP_EOL;
            echo "================================================================\n\n\n";
        }

        echo 'Done.'.PHP_EOL;
    }

    final public function listIdentities()
    {
        $identities = [];

        $visitors = $this->traverser->getVisitors();

        array_walk($visitors, function (NodeVisitorInterface $visitor) use (&$identities) {
            if ($visitor instanceof VisitorInterface) {
                $currentIdentities = $visitor->getIdentities();
                $identities = array_merge($identities, $currentIdentities);
            }
        });

        return $identities;
    }

    /**
     * @param Filesystem $filesystem
     *
     * @return array
     */
    private function listFiles(Filesystem $filesystem)
    {
        $path = '/';

        $files = $filesystem->listContents($path, true);

        $whitelist = $this->whitelist;

        if (count($whitelist) > 0) {
            $files = array_filter($files, function ($file) use ($whitelist) {
                return in_array($file['path'], $whitelist, true);
            });
        }

        $blacklist = $this->blacklist;

        if (count($blacklist) > 0) {
            $files = array_filter($files, function ($file) use ($blacklist) {
                $keep = true;

                foreach ($blacklist as $ignorePath) {
                    $keep = $keep && strpos($file['path'], $ignorePath) !== 0;

                    if ($keep === false) {
                        break;
                    }
                }

                return $keep;
            });
        }

        return $files;
    }

    /**
     * @param Filesystem $filesystem
     *
     * @throws \League\Flysystem\FileNotFoundException
     * @throws ParserException
     */
    private function scanFileSystem(Filesystem $filesystem)
    {
        $files = $this->listFiles($filesystem);

        foreach ($files as $file) {
            $path = $file['path'];

            if ($file['type'] === 'file'/* @TODO: && $file['extension'] === 'php'*/) {
                // @NOTE: Directories can be ignore as `listFile()` is recursive
                echo '================================================================' . PHP_EOL;
                vprintf(' =====> Entering file "%s"%s', [$path, PHP_EOL]);

                $content = $filesystem->read($path);

                $tree = $this->parser->parse($content);

                $visitors = $this->traverser->getVisitors();

                $lexer = $this->parser->getLexer();

                $this->callMethodOnVisitors($visitors, 'setFileName', [$path]);
                $this->callMethodOnVisitors($visitors, 'setTokens', [$lexer->getTokens()]);
                $this->callMethodOnVisitors($visitors, 'setTree', [$tree]);

                $identities = $this->traverser->traverse($tree);

                /* @FIXME: Having `$this->result[$path]` doesn't work as we want to list all declarations and usages, not just per file/folder
                 *
                 * The only real solution is to have a full lists of all declared
                 * classes and functions and a list of all of the usages and
                 * cross-reference those lists.
                 *
                 * 1. Do a first pass to get all declarations of functions and
                 *    classes and class methods (variables? constants?)
                 * 2. Do a second pass to get all usage of functions and classes
                 *    and class methods (variables? constants?).
                 * 3. Traverse the usage list against the declaration list
                 *
                 * In principle the first and second _could_ be done at the same
                 * time, as long as "tokens" are used instead of trying to
                 * resolve anything at that point.
                 *
                 * This would also resolve the problem with values hidden in
                 * variables, as both the declaration of "$a" and the usage of
                 * "$a" would be in the same scope.
                 *
                 * To avoid segfaults and in order to run atomic/re-use results,
                 * findings (parsing results) should be written to file(s).
                 */
                $this->result[] = $identities;

                echo '----------------------------------------------------------------' . PHP_EOL;
                echo ' <==== Leaving file' . PHP_EOL;
                echo "================================================================\n\n";
            }
        }
    }

    /**
     * @param NodeVisitorInterface[] $visitors
     * @param string $methodName
     * @param array $parameters
     */
    private function callMethodOnVisitors(array $visitors, $methodName, array $parameters)
    {
        array_walk($visitors, function (NodeVisitorInterface $visitor) use ($methodName, $parameters) {
            if (method_exists($visitor, $methodName)) {
                $visitor->{$methodName}(...$parameters);
            }
        });
    }
}

/*EOF*/
