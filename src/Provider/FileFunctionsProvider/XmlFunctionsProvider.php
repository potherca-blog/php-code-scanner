<?php

namespace Potherca\Scanner\Provider\FileFunctionsProvider;

use Potherca\Scanner\Provider\ProviderInterface;

class ImageFunctionsProvider implements ProviderInterface
{
    /** @var array */
    private static $functions = [

        // @TODO: http://php.net/manual/en/refs.xml.php
        /*/ DOM — Document Object Model /*/
        /*/ libxml /*/
        /*/ SDO — Service Data Objects /*/
        /*/ SDO-DAS-Relational — SDO Relational Data Access Service /*/
        /*/ SDO DAS XML — SDO XML Data Access Service /*/
        /*/ SimpleXML /*/
        /*/ WDDX /*/
        /*/ XMLDiff — XML diff and merge /*/
        /*/ XML Parser /*/
        /*/ XMLReader /*/
        /*/ XMLWriter /*/
        /*/ XSL /*/

        /*/ Create /*/

        /*/ Read /*/

        /*/ Update /*/

        /*/ Delete /*/
    ];

    /**
     * @return array
     */
    final public function provide()
    {
        return self::$functions;
    }
}

/*EOF*/
