<?php

/**
 * @param $data
 *
 * @param bool $uppercase
 *
 * @return string
 *
 * @source: https://github.com/clue/php-hexdump/blob/master/src/Clue/Hexdump/Hexdump.php
 */
function hexdump ($data, $uppercase = true)
{
    // Init
    $hexi   = '';
    $ascii  = '';
    $dump   = '';
    $offset = 0;
    $len    = strlen($data);
    // Upper or lowevr case hexadecimal
    $x = ($uppercase === false) ? 'x' : 'X';
    // Iterate string
    for ($i = $j = 0; $i < $len; $i++)
    {
        // Convert to hexidecimal
        $hexi .= sprintf("%02$x ", ord($data[$i]));
        // Replace non-viewable bytes with '.'
        if (ord($data[$i]) >= 32) {
            $ascii .= $data[$i];
        } else {
            $ascii .= '.';
        }
        // Add extra column spacing
        if ($j === 7 && $i !== $len - 1) {
            $hexi  .= ' ';
            $ascii .= ' ';
        }
        // Add row
        if (++$j === 16 || $i === $len - 1) {
            // Join the hexi / ascii output
            $dump .= sprintf("%04$x  %-49s  %s", $offset, $hexi, $ascii);
            // Reset vars
            $hexi   = $ascii = '';
            $offset += 16;
            $j      = 0;
            // Add newline
            if ($i !== $len - 1) {
                $dump .= "\n";
            }
        }
    }
    // Finish dump
    $dump .= "\n";
    return $dump;
}

$functionName1 = 'mysql_​fetch_​array'; // <= this line contains zero-width characters
$functionName2 = 'mysql_fetch_array';


/*
$found = in_array($functionName1, $functions);
var_dump($found);

$found = in_array($functionName2, $functions);
var_dump($found);
*/

var_dump(hexdump($functionName1));
var_dump(hexdump($functionName2));
