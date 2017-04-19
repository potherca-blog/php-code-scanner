<?php

namespace Potherca\Scanner\CommandLineInterface;

// @FIXME: Add a way to add userland Database / Email / Filesystem /etc. calls

class Command
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const EXIT_OK = 0;
    const EXIT_NOT_ENOUGH_PARAMETERS = Arguments::ERROR_NOT_ENOUGH_PARAMETERS;
    const EXIT_SUBJECT_NOT_FILE_OR_FOLDER = Arguments::ERROR_SUBJECT_NOT_FILE_OR_FOLDER;
    const EXIT_UNKNOWN_ERROR = 65;  // 'An unknown error occurred';

    /** @var Arguments */
    private $arguments;
    /** @var string */
    private $errorMessage;
    /** @var int */
    private $exitCode = 0;
    /** @var string */
    private $name;
    /** @var array */
    private $parameters = [
        /* parameter => description */
        '--subject <path-to-scan>' => '',
        '[--identity=<identity-to-scan-for>]' => '',
        '[--ignore <path-to-ignore>]' => '',
        // @TODO: '[--source-directory <path-to-source>]' => '', // parse the ENTIRE code-base, only report output for --subject(s)
        // @TODO: '[--list-identities]' => '',
        // @TODO: '[--list-php-versions]' => '',
        // @TODO: '[--php-version=<php-version>]' => '',
        // @TODO: '[--verbose]' => '',
    ];

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @return string */
    final public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    final public function getExitCode()
    {
        return $this->exitCode;
    }

    final public function getLongOptions()
    {
        return [
            'subject:',
            'ignore::',
            'identity::',
        ];
    }

    final public function getShortOptions()
    {
        return '';
    }

    final public function getShortUsage()
    {
        return vsprintf(
            'Usage: %s %s',
            [
                $this->name,
                implode(' ', array_keys($this->parameters)),
            ]
        );
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct($name)
    {
        $this->name = $name;
    }

    final public function argumentsAreValid()
    {
        return count($this->parameters) < 1;
    }

    final public function convertToJson($result)
    {
        return json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
    }

    final public function parseArguments(Arguments $arguments)
    {
        $this->arguments = $arguments;

        $arguments->parse();

        $argumentsAreValid = $arguments->isValid();

        if ($argumentsAreValid === false) {
            $this->errorMessage = $arguments->getErrorMessage();
            $this->exitCode = $arguments->getErrorCode();
        }

        return $argumentsAreValid;
    }
}

/*EOF*/
