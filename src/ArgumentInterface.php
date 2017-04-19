<?php

namespace Potherca\Scanner;

interface ArgumentInterface
{
    /** @return array */
    public function getBlacklist();

    /** @return array */
    public function getDirectories();

    /** @return array */
    public function getWhitelist();

    /** @return int */
    public function getPhpVersion();

    /** @return array */
    public function getIdentifiers();
}

/*EOF*/
