<?php

namespace Potherca\Scanner;

use Monolog\Logger as InternalLogger;

class Logger
{
    /** @var string */
    private $codeLine = '';
    /** @var InternalLogger */
    private $logger;
    /** @var string */
    private $tokenLine = '';

    /** @return string */
    public function getTokenLine()
    {
        return $this->tokenLine;
    }

    /** @return string */
    public function getCodeLine()
    {
        return $this->codeLine;
    }

    /** @param InternalLogger $logger */
    final public function setLogger(InternalLogger $logger)
    {
        $this->logger = $logger;
    }

    final public function concatCodeLine($message)
    {
        $this->codeLine .= $message;
    }

    final public function concatTokenLine($message)
    {
        $this->tokenLine .= $message;
    }

    final public function isDebug()
    {
        return $this->logger instanceof Logger;
    }

    final public function log($message, $context) {
        if ($this->isDebug()) {
            $this->logger->addDebug($message, [$context]);
        }
    }

    final public function resetCodeLine($message = '')
    {
        $this->codeLine = $message;
    }

    final public function resetTokenLine($message = '')
    {
        $this->tokenLine = $message;
    }
}
