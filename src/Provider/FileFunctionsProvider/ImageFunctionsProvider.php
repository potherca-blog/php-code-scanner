<?php

namespace Potherca\Scanner\Provider\FileFunctionsProvider;

use Potherca\Scanner\Provider\ProviderInterface;

class ImageFunctionsProvider implements ProviderInterface
{
    /** @var array */
    private static $functions = [
        /* GD */
        'exif_imagetype',
        'exif_read_data',
        'exif_thumbnail',
        'getimagesize',
        'image2wbmp',
        'imagebmp',
        'imagecreatefrombmp',
        'imagecreatefromgd',
        'imagecreatefromgd2',
        'imagecreatefromgd2part',
        'imagecreatefromgif',
        'imagecreatefromjpeg',
        'imagecreatefrompng',
        'imagecreatefromstring',
        'imagecreatefromwbmp',
        'imagecreatefromwebp',
        'imagecreatefromxbm',
        'imagecreatefromxpm',
        'imagegd',
        'imagegd2',
        'imagegif',
        'imagejpeg',
        'imagepng',
        'imagewbmp',
        'imagewebp',
        'imagexbm',
        'iptcembed',
        'jpeg2wbmp',
        'png2wbmp',
        'read_exif_data',

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
