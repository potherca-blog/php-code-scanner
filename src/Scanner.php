<?php

namespace Potherca\Scanner;

use League\Flysystem\Filesystem;

class Scanner
{
    /** @var Filesystem */
    private $filesystem;
    /** @var array */
    private $result;

    final public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    final public function scan($folder)
    {
        $contents = $this->filesystem->listContents($folder, true);

        foreach ($contents as $item) {
            $path = $item['path'];
            // if ($item['type'] === 'folder') {
            // @TODO: ...recurse...
            //} else {

            $contents = $this->filesystem->read($path);

            $this->result[$path] = $this->parse($contents);

            //)
        }

        return $this->result;
    }

    private function parse($contents)
    {
        $result = [
            'declared' => [],
            'call-internal' => [],
            'call-userland' => [],
        ];

        $tokens = token_get_all($contents);

        foreach ($tokens as $index => $token) {
            if (is_string($token[0]) === false) {
                $token[0] = token_name($token[0]);
                $tokens[$index][0] = $token[0];
            }

            switch ($token[0]) {
                /*/ function definition /*/
                case 'T_FUNCTION':
                    $functionName = $tokens[($index + 2)];
                    $result['declared'][] = $functionName[1];
                break;

                /*/ potential function usage /*/
                case 'T_STRING':
                    $candidate = $token[1];

                    if ($this->isInternalFunction($candidate)) {
                        $result['call-internal'][] = $candidate;
                    } else {
                        // @FIXME: Some of these are class methods!
                        $result['call-userland'][] = $candidate;
                    }
                break;
            }
        }

        $result['declared'] = array_unique($result['declared']);
        $result['call-internal'] = array_unique($result['call-internal']);
        $result['call-userland'] = array_unique($result['call-userland']);

        return $result;
    }

    private function isInternalFunction($functionName)
    {
        static $internalFunctions;

        if ($internalFunctions === null) {
            $allFunctions = get_defined_functions();
            $internalFunctions = $allFunctions['internal'];
        }

        return in_array($functionName, $internalFunctions);
    }
}

/*EOF*/
