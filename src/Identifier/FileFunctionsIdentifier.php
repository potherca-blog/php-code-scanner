<?php

namespace Potherca\Scanner\Identifier;

use Potherca\Scanner\Identity\IdentityType;
use Potherca\Scanner\Node\NodeType;

/* @FIXME: Use sub-class instead of combining lists here */

class FileFunctionsIdentifier extends AbstractSingleTypeIdentifier
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var array */
    private static $functions = [
        // @TODO: http://php.net/manual/en/refs.fileprocess.file.php

        // @TODO: http://php.net/manual/en/refs.compression.php

        /*/ Create /*/
        'link',
        'copy',
        'mkdir',
        'move_uploaded_file',
        'symlink',

        /*/ Read /*/
        'dir',
        'dirname',
        'file',
        'file_exists',
        'file_get_contents',
        'fileatime',
        'filectime',
        'filegroup',
        'fileinode',
        'filemtime',
        'fileowner',
        'fileperms',
        'filesize',
        'filetype',
        'fopen',
        'fread',
        'getcwd',
        'get_meta_tags',
        'glob',
        'is_file',
        'is_writable',
        'is_dir',
        'is_executable',
        'is_link',
        'is_readable',
        'is_writeable',
        'linkinfo',
        'lstat',
        'opendir',
        'parse_ini_file',
        'pathinfo',
        'readfile',
        'readlink',
        'realpath',
        'scandir',
        'stat',
        'sys_get_temp_dir',

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

        /* simplexml */
        'simplexml_load_file',
        // @TODO: SimpleXMLElement::asXML

        /* @TODO: DomDocument */
        // 'DOMDocument::load',
        // 'DOMDocument::loadHTMLFile',
        // 'DOMDocument::relaxNGValidate',
        // 'DOMDocument::save',
        // 'DOMDocument::saveHTMLFile',
        // 'DOMDocument::schemaValidate ',

        /*/ Update /*/
        'chdir',
        'chgrp',
        'chmod',
        'chown',
        'chroot',
        'file_put_contents',
        'fwrite',
        'lchgrp',
        'lchown',
        'rename',
        'touch',
        'umask',

        /*/ Delete /*/
        'delete',
        'rmdir',
        'unlink',
    ];

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function getSupportedNodeTypes()
    {
        return [
            NodeType::EXPR_FUNC_CALL,
        ];
    }

    final public function getIdentityType()
    {
        return IdentityType::USAGE_INTERNAL_FILESYSTEM_CALL;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @return array
     */
    final public function getIdentifiers()
    {
        return self::$functions;
    }
}

/*EOF*/
