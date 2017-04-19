<?php

namespace Potherca\Scanner\CommandLineInterface;

use Potherca\Scanner\ArgumentInterface;

class Arguments implements ArgumentInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const ERROR_NOT_ENOUGH_PARAMETERS = 66;
    const ERROR_SUBJECT_NOT_FILE_OR_FOLDER = 67;

    /** @var array */
    private $arguments;
    /** @var array */
    private $blacklist = [];
    /** @var array */
    private $directories=[];
    /** @var int */
    private $errorCode = 0;
    /** @var string  */
    private $errorMessage = '';
    /** @var array */
    private $whitelist = [];

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @return array */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /** @return array */
    public function getDirectories()
    {
        return $this->directories;
    }

    /** @return int */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /** @return string */
    final public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /** @return array */
    public function getWhitelist()
    {
        return $this->whitelist;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    final public function parse()
    {
        $arguments = $this->arguments;

        if (count($arguments) < 1) {
            $this->errorMessage = 'Not enough parameters given';
            $this->errorCode = self::ERROR_NOT_ENOUGH_PARAMETERS;
        }

        /*/ Directories and Whitelist /*/
        if ($this->isValid() === true) {
            $subjects = $arguments['subject'];

            if (is_array($subjects) === false) {
                $subjects = [$subjects];
            }

            /** @noinspection ForeachSourceInspection */
            foreach ($subjects as $subject) {
                // @TODO: Use Symfony Finder instead of hard-coded IO lookup
                if (is_dir($subject)) {
                    $this->directories[] = $subject;
                } elseif (is_file($subject)) {
                    $this->whitelist[] = basename($subject);
                    $this->directories[] = dirname($subject);
                } else {
                    $this->errorMessage = sprintf('Given subject "%s" is not a file or directory', $subject);
                    $this->errorCode = self::ERROR_SUBJECT_NOT_FILE_OR_FOLDER;
                }
            }

        }

        /*/ Blacklist /*/
        if ($this->isValid() === true) {
            if (array_key_exists('ignore', $arguments)) {
                $ignore = $arguments['ignore'];

                if (is_scalar($ignore)) {
                    $ignore = [$ignore];
                }

                $this->blacklist = array_filter($ignore);
            }
        }
    }

    final public function isValid()
    {
        return $this->errorCode === 0;
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
}

/*EOF*/
