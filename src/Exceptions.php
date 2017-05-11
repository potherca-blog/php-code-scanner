<?php

namespace Potherca\Scanner\Exception;

/////////////////////////////// GENERIC EXCEPTIONS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
class NotYetImplementedException extends \Exception {}

//////////////////////////////// LOGIC EXCEPTIONS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
class LogicException extends \LogicException {}

class InvalidArgumentException extends LogicException {}

/////////////////////////////// RUN TIME EXCEPTIONS \\\\\\\\\\\\\\\\\\\\\\\\\\\\
class RuntimeException extends \RuntimeException {}

class ParserException extends RuntimeException {
    const PHP_PARSE_ERROR = 1;
    const MYSQL_PARSE_ERROR = 2;
}

class UnexpectedValueException extends RuntimeException {}

/*EOF*/
