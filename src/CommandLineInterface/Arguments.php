<?php

namespace Potherca\Scanner\CommandLineInterface;

use Potherca\Scanner\ArgumentInterface;
use Potherca\Scanner\Identifier\IdentifierOption;
use Symfony\Component\Finder\Finder;

class Arguments implements ArgumentInterface
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const ERROR_NOT_ENOUGH_PARAMETERS = 66;
    const ERROR_SUBJECT_NOT_FILE_OR_FOLDER = 67;
    const ERROR_IDENTIFIER_NOT_FILE_OR_FOLDER = 68;

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
    /** @var Finder */
    private $finder;
    /** @var  array */
    private $identifiers = [];
    /** @var bool */
    private $isHelp = false;
    /** @var bool */
    private $isVerbose = false;
    /** @var int */
    private $phpVersion;
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
    public function getIdentifiers()
    {
        return $this->identifiers;
    }

    /** @return int */
    public function getPhpVersion()
    {
        return $this->phpVersion;
    }

    /** @return array */
    public function getWhitelist()
    {
        return $this->whitelist;
    }

    /** @return bool */
    public function isHelp()
    {
        return $this->isHelp;
    }

    /** @return bool */
    public function isVerbose()
    {
        return $this->isVerbose;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(array $arguments, Finder $finder)
    {
        $this->arguments = $arguments;
        $this->finder = $finder;
    }

    final public function parse()
    {
        $arguments = $this->arguments;
        // @TODO: Use Symfony Finder instead of hard-coded IO lookup
        $finder = $this->finder;

        $this->isVerbose = array_key_exists('verbose', $arguments);

        if (array_key_exists('help', $arguments) === true) {
            $this->isHelp = true;
        } else {

            /*/ Make sure the minimum is met /*/
            if (array_key_exists('subject', $arguments) === false) {
                $this->errorMessage = 'Not enough parameters given';
                $this->errorCode = self::ERROR_NOT_ENOUGH_PARAMETERS;
            }

            /*/ Set PHP version /*/
            $this->loadPhpVersion($arguments);

            /*/ Directories and Whitelist /*/
            $this->loadDirectories($arguments);

            /*/ Blacklist /*/
            $this->loadBlackList($arguments);

            /*/ Identifiers /*/
            $this->loadIdentifiers();
        }
    }

    final public function isValid()
    {
        return $this->errorCode === 0;
    }

    final public function loadIdentifiers()
    {
        $this->loadSpecificIdentifiers(IdentifierOption::INTERNAL_IDENTIFIERS);
        $this->loadSpecificIdentifiers(IdentifierOption::IDENTIFIERS);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param string $key
     */
    private function loadSpecificIdentifiers($key)
    {
        $arguments = $this->arguments;

        if ($this->isValid() === true) {
            if (array_key_exists($key, $arguments)) {
                $identifiers = $arguments[$key];

                if (is_scalar($identifiers)) {
                    $identifiers = [$identifiers];
                }

                $identifierPaths = [];

                array_walk($identifiers, function ($identifier) use (&$identifierPaths) {
                    /* @NOTE: Remove any trailing slash */
                    $identifier = rtrim($identifier, '/');

                    if (is_dir($identifier)) {
                        $files = glob($identifier . '/*.php');

                        $identifierPaths = array_merge($identifierPaths, $files);
                    } elseif (is_file($identifier)) {
                        $identifierPaths[] = $identifier;
                    } else {
                        $this->errorMessage = sprintf('Given identifier "%s" is not a file or directory', $identifier);
                        $this->errorCode = self::ERROR_SUBJECT_NOT_FILE_OR_FOLDER;
                    }
                });

                $identifierPaths = array_filter($identifierPaths);
                $this->identifiers = array_merge($this->identifiers, $identifierPaths);
            }
        }
    }

    /**
     * @param $arguments
     */
    private function loadDirectories($arguments)
    {
        if ($this->isValid() === true && array_key_exists('subject', $arguments)) {

            $subjects = $arguments['subject'];

            if (is_array($subjects) === false) {
                $subjects = [$subjects];
            }

            /** @noinspection ForeachSourceInspection */
            foreach ($subjects as $subject) {
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
    }

    /**
     * @param $arguments
     */
    private function loadBlackList($arguments)
    {
        if ($this->isValid() === true) {
            $key = 'ignore';
            if (array_key_exists($key, $arguments)) {
                $ignore = $arguments[$key];

                if (is_scalar($ignore)) {
                    $ignore = [$ignore];
                }

                $this->blacklist = array_filter($ignore);
            }
        }
    }

    /**
     * @param $arguments
     */
    private function loadPhpVersion($arguments)
    {
        $key = IdentifierOption::PHP_VERSION;
        if (array_key_exists($key, $arguments)) {
            $version = $arguments[$key];

            IdentifierOption::assertExists($key);

            $this->phpVersion = (int)$version;
        }
    }
}

/*EOF*/
