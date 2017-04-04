<?php

namespace Potherca\Scanner;

use League\Flysystem\Filesystem;

class Scanner
{
    /** @var Filesystem */
    private $filesystem;

    final public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    final public function scan($folder)
    {
        $contents = $this->filesystem->listContents($folder, true);
        
        foreach ($contents as $item) {
            // @TODO: if ($item['type'] === 'folder') {}
            $this->parse($item);
        }
    }

    private function parse($item)
    {
        // get file content
        $contents = $this->filesystem->read($item['path']);
        $tokens = token_get_all($contents);
        
        foreach ($tokens as &$token) {
            $token[0] = token_name($token[0]);
        }
        
        // T_STRING = function usage
        // T_FUNCTION = "function" keyword
        var_dump($tokens);exit;
        // create AST from content
        // filter relevant fields from AST
    }
}

/*EOF*/