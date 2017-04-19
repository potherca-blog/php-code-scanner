<?php

namespace Potherca\Scanner\Exception;

class ParserException extends RuntimeException {
    const PHP_PARSE_ERROR = 'php';
    const MYSQL_PARSE_ERROR = 'mysql';
}

/*EOF*/
