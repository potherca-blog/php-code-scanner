<?php

if (getenv('COMPOSER_VENDOR_DIR') !== false) {
    $vendorDirectory = getenv('COMPOSER_VENDOR_DIR') . '/vendor';
} else {
    $vendorDirectory = dirname(__DIR__) . '/vendor';
}

require_once $vendorDirectory .'/autoload.php';
require_once __DIR__.'/Exceptions.php';

/*EOF*/
