<?php

namespace Potherca\Scanner;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PhpParser\NodeVisitor;
use Potherca\Scanner\Exception\ParserException;

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
            $prefix = $adapter->getPathPrefix();
            echo '================================================================' . PHP_EOL;
            vprintf(' =====> Entering folder "%s"%s', [$prefix, PHP_EOL]);

            $this->scanFileSystem($prefix, $filesystem);

            echo '----------------------------------------------------------------' . PHP_EOL;
            echo ' <==== Leaving folder'.PHP_EOL;
            echo "================================================================\n\n\n";
        }

        echo 'Done.'.PHP_EOL;
    }

    /**
     * @param Filesystem $filesystem
     * @param string $folder
     *
     * @return array
     */
    private function listFiles(Filesystem $filesystem, $folder = '/')
    {
        $files = $filesystem->listContents($folder, true);

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
     * @param $prefix
     * @param Filesystem $filesystem
     *
     * @throws \League\Flysystem\FileNotFoundException
     * @throws ParserException
     */
    private function scanFileSystem($prefix, Filesystem $filesystem)
    {
        $files = $this->listFiles($filesystem);

        foreach ($files as $file) {
            $path = $file['path'];

            if ($file['type'] === 'dir') {
                $this->scanFileSystem($prefix.'/'.$path, $filesystem);
            } else {

                echo '================================================================' . PHP_EOL;
                vprintf(' =====> Entering file "%s"%s', [$path, PHP_EOL]);
                // @TODO: Only parse (valid) php files
                $content = $filesystem->read($path);

                $tree = $this->parser->parse($content);

                $visitors = $this->traverser->getVisitors();

                $lexer = $this->parser->getLexer();

                array_walk($visitors, function (NodeVisitor $visitor) use ($lexer, $tree) {

                    if (method_exists($visitor, 'setTokens')) {
                        $visitor->setTokens($lexer->getTokens());
                    }

                    if (method_exists($visitor, 'setTree')) {
                        $visitor->setTree($tree);
                    }
                });

                $identities = $this->traverser->traverse($tree);

                /* @FIXME: Having `$this->result[$prefix][$path]` doesn't work as we want to list all declarations and usages, not just per file/folder
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
                $this->result[$prefix][$path] = $identities;

                echo '----------------------------------------------------------------' . PHP_EOL;
                echo ' <==== Leaving file' . PHP_EOL;
                echo "================================================================\n\n";
            }
        }
    }
}

/*EOF*/
