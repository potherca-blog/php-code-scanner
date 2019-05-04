<?php

namespace Potherca\Scanner;

use Potherca\Scanner\Provider\DatabaseFunctionsProvider;
use Potherca\Scanner\Provider\FileFunctionsProvider;
use Potherca\Scanner\Provider\InternalFunctionsProvider;

class Provider
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var DatabaseFunctionsProvider */
    private $databaseFunctionsProvider;
    /** @var FileFunctionsProvider */
    private $fileFunctionsProvider;
    /** @var InternalFunctionsProvider */
    private $internalFunctionsProvider;

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(
        DatabaseFunctionsProvider $databaseFunctionsProvider,
        FileFunctionsProvider $fileFunctionsProvider,
        InternalFunctionsProvider $internalFunctionsProvider
    ) {
        $this->internalFunctionsProvider = $internalFunctionsProvider;
        $this->fileFunctionsProvider = $fileFunctionsProvider;
        $this->databaseFunctionsProvider = $databaseFunctionsProvider;
    }

    /**
     * @param string $functionName
     *
     * @return bool
     */
    final public function isDatabaseFunction($functionName)
    {
        return $this->isProvidedFunction($this->databaseFunctionsProvider, $functionName);
    }

    /**
     * @param string $functionName
     *
     * @return bool
     */
    final public function isFileFunction($functionName)
    {
        return $this->isProvidedFunction($this->fileFunctionsProvider, $functionName);
    }

    /**
     * @param string $functionName
     *
     * @return bool
     */
    final public function isInternalFunction($functionName)
    {
        return $this->isProvidedFunction($this->internalFunctionsProvider, $functionName);
    }

        private public function isProvidedFunction($provider, $functionName)
    {
        $functions = $provider->provide();

        return in_array($functionName, $functions, false);
    }
}

/*EOF*/
