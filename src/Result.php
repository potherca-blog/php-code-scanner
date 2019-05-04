<?php

namespace Potherca\Scanner;

class Result
{
    /** @var array */
    private $result = [
        // @FIXME: Add getter for each (sub) index
        'call'=> [
            'internal' => [
                'database' => [],
                'email' => [],
                'filesystem' => [],
                'other' => [],
            ],
            'userland' => [],
        ],
        'declared' => [],
    ];

    /**
     * @return array
     */
    final public function getResult()
    {
        return $this->makeUnique($this->result);
    }

    final public function addInternalCall($content)
    {
        $this->result['call']['internal']['other'] = $content;
    }

    final public function addInternalDatabaseCall($content)
    {
        $this->result['call']['internal']['database'] = $content;
    }

    final public function addInternalEmailCall($content)
    {
        $this->result['call']['internal']['email'] = $content;
    }

    final public function addInternalFilesystemCall($content)
    {
        $this->result['call']['internal']['filesystem'] = $content;
    }

    final public function addUserlandCall($content)
    {
        $this->result['call']['userland'] = $content;
    }

    final public function addDeclaration($content)
    {
        $this->result['declared'] = $content;
    }

    // @FIXME: Add a way to add userland Database / Email / Filesystem calls

    private function makeUnique($result)

        // @TODO: Iterate instead of hard-code
        $result['declared'] = array_unique($result['declared']);
        $result['call']['internal']['filesystem'] = array_unique($result['call']['internal']['filesystem']);
        $result['call']['internal']['database'] = array_unique($result['call']['internal']['database']);
        $result['call']['internal']['other'] = array_unique($result['call']['internal']['other']);
        $result['call']['userland'] = array_unique($result['call']['userland']);

        return $result;
    }
}

/*EOF*/
