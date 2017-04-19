<?php

namespace Potherca\Scanner\CommandLineInterface;

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
    private $exitCode = self::EXIT_OK;
    /** @var string */
    private $name;
    /** @var array */
    private $parameters = [
        /* parameter => description */
        '--subject <path-to-scan>' => 'Path to directory or file to scan. Recurses into directories',
        '[--help]' => 'Display this information',
        '[--identifier=<path-to-identifier>]' => 'Path to directory or file declaring custom identifiers. Does not recurse into directories',
        '[--ignore=<path-to-ignore>]' => 'Path to directory or file to exclude from scanning',

        // @TODO: '[--identity=<identity-to-scan-for>]' => 'Only output information for specified identities.'
        //                                               . 'Use "--list-identities" flag for all available identities',
        // @TODO: '[--source-directory=<path-to-source>]' => 'Path to directory or file to scan but not output information about',
        // @TODO: '[--list-identities]' => 'List all available identities than can be identified',
        // @TODO: '[--list-php-versions]' => 'List valid PHP versions that can be scanned',
        // @TODO: '[--php-version=<php-version>]' => 'Specific PHP version to scan. Defaults to PHP5.6',
        // @TODO: '[--verbose]' => 'Output more detailed information about what the scanner is doing',
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
        /*
         * The options parameter may contain the following elements:
         *
         * - Individual characters (do not accept values)
         * - Characters followed by a colon (parameter requires value)
         * - Characters followed by two colons (optional value)
         *
         * Option values are the first argument after the string. If a value is
         * required, it does not matter whether the value has leading white space
         * or not. Optional values do not accept " " (space) as a separator.
         */
        return [
            'subject:',
            'help',
            'identifier::',
            'ignore::',
        ];
    }

    final public function getShortOptions()
    {
        return '';
    }

    final public function getFullUsage()
    {
        $parameters = $this->parameters;

        $usage = $this->getShortUsage().PHP_EOL;

        $length = max(array_map('strlen', array_keys($parameters)));
        $format = "%s%- {$length}s %s";

        array_walk($parameters, function ($description, $name) use (&$usage, $format) {
            $usage .= vsprintf($format, ["\n\t", $name, $description]);
        });

        return $usage;
    }

    final public function getShortUsage()
    {
        return vsprintf(
            '%sUsage: %s %s',
            [
                PHP_EOL,
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
