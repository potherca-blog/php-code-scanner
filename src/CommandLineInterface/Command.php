<?php

namespace Potherca\Scanner\CommandLineInterface;

use Potherca\Scanner\Identity;
use Potherca\Scanner\ScannerFactory as Factory;

class Command
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const EXIT_OK = 0;
    const EXIT_NOT_ENOUGH_PARAMETERS = Arguments::ERROR_NOT_ENOUGH_PARAMETERS;
    const EXIT_SUBJECT_NOT_FILE_OR_FOLDER = Arguments::ERROR_SUBJECT_NOT_FILE_OR_FOLDER;
    const EXIT_UNKNOWN_ERROR = 65;  // 'An unknown error occurred';

    const MAIN_COMMAND = '__MAIN__';
    const HELP_COMMAND = '__HELP__';

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
        // @TODO: '[--list-php-versions]' => 'List valid PHP versions that can be scanned',
        // @TODO: '[--php-version=<php-version>]' => 'Specific PHP version to scan. Defaults to PHP5.6',
        // @TODO: '[--verbose]' => 'Output more detailed information about what the scanner is doing',
    ];
    /** @var array  */
    private $commands = [
        self::HELP_COMMAND => 'getFullUsage',
        self::MAIN_COMMAND => 'scan',
        'list-identities' => 'listIdentities',
    ];
    /** @var array */
    private $rawArguments;

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

        $usage .= vsprintf(
            '%1$s%1$s  or: %2$s command [options]%1$s',
            [
                PHP_EOL,
                $this->name,
            ]
        );

        $usage .= PHP_EOL.'Available commands:'.PHP_EOL;

        $commands = ['list-identities', 'help'];
        array_walk($commands, function ($command) use (&$usage) {
            $usage .= vsprintf('%s    %s', [PHP_EOL, $command]);
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

    /**
     * @param string $name Name of this command
     * @param array $rawArguments All arguments passed to this command
     */
    final public function __construct($name, array $rawArguments)
    {
        $this->name = $name;
        $this->rawArguments = $rawArguments;
    }

    /**
     * @param Arguments $arguments
     *
     * @return array
     */
    final public function call(Arguments $arguments)
    {
        /*/ Default values /*/
        $command = self::MAIN_COMMAND;

        $argumentsAreValid = true;

        $output = [
            'exit-code' => self::EXIT_UNKNOWN_ERROR,
            'message' => 'An unknown error occurred',
            'stream' => STDERR,
        ];

        $rawArguments = $this->rawArguments;

        if (isset($rawArguments[1]) && $rawArguments[1]{0} !== '-') {
            if ($rawArguments[1] === 'help') {
                $command = self::HELP_COMMAND;
            } elseif (array_key_exists($rawArguments[1], $this->commands) === true) {
                $command = $rawArguments[1];
            } else {
                $argumentsAreValid = false;
            }
        }

        $argumentsAreValid = $argumentsAreValid && $this->parseArguments($command, $arguments);

        if ($argumentsAreValid === false) {
            $output = [
                'exit-code' => $this->getExitCode(),
                'message' => $this->getErrorMessage().PHP_EOL.$this->getShortUsage(),
                'stream' => STDERR,
            ];
        } else {

            if ($arguments->isHelp() === true) {
                $command = self::HELP_COMMAND;
            }

            try {
                $methodName = $this->commands[$command];

                $message = $this->{$methodName}($arguments);

                $output = [
                    'exit-code' => $this->getExitCode(),
                    'message' => $message,
                    'stream' => STDOUT,
                ];
            } catch (\Exception $exception) {
                $output = [
                    'exit-code' => 65, // Generic error
                    'message' => $exception->getMessage(),
                    'stream' => STDERR,
                ];
            }
        }

        return $output;
    }

    final public function convertToJson($result)
    {
        return json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;
    }

    /**
     * @param string $command
     * @param Arguments $arguments
     *
     * @return bool
     */
    final public function parseArguments($command, Arguments $arguments)
    {
        $this->arguments = $arguments;


        if ($command !== self::MAIN_COMMAND) {
            $arguments->loadIdentifiers();
            $argumentsAreValid = true;
        } else {
            // Currently only the main command accepts parameters
            $arguments->parse();

            $argumentsAreValid = $arguments->isValid();

            if ($argumentsAreValid === false) {
                $this->errorMessage = $arguments->getErrorMessage();
                $this->exitCode = $arguments->getErrorCode();
            }
        }

        return $argumentsAreValid;
    }

    private function listIdentities(Arguments $arguments)
    {
        $message = 'The following IdentityTypes can be identified:'.PHP_EOL;

        $scanner = $this->createScanner($arguments);

        $identities = $scanner->listIdentities();

        $delimiter = "\n - ";

        $message .= $delimiter . implode($delimiter, $identities);

        return $message;
    }

    /**
     * @param Arguments $arguments
     *
     * @return array
     */
    private function scan(Arguments $arguments)
    {
        $scanner = $this->createScanner($arguments);

        $scanner->scan();

        $results = $scanner->getResult();

        /* Output */
        $message = [];
        array_walk($results, function ($result) use (&$message) {
            array_walk($result, function (Identity $identity) use (&$message) {
                $message[] = (string)$identity;
            });
        });

        // @FIXME: Using `array_unique` removes duplicate entries, but DUPLICATE ENTRIES SHOULD NOT EXIST!
        $message = array_unique($message);

        natcasesort($message);

        return implode("\n", $message);
    }

    /**
     * @param Arguments $arguments
     *
     * @return \Potherca\Scanner\Scanner
     */
    private function createScanner(Arguments $arguments)
    {
        $factory = new Factory($arguments);

        return $factory->create();
    }
}

/*EOF*/
