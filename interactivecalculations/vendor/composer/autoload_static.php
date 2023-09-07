<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit21f7f30ffb95241948b10b86129b96ce
{
    public static $prefixLengthsPsr4 = array (
        'i' => 
        array (
            'intercalcus\\fe\\displayTypes\\' => 28,
            'intercalcus\\fe\\' => 15,
            'intercalcus\\db\\model\\' => 21,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
        'C' => 
        array (
            'CustomJwtAuth\\' => 14,
        ),
        'A' => 
        array (
            'Amenadiel\\JpGraph\\Util\\' => 23,
            'Amenadiel\\JpGraph\\Themes\\' => 25,
            'Amenadiel\\JpGraph\\Text\\' => 23,
            'Amenadiel\\JpGraph\\Plot\\' => 23,
            'Amenadiel\\JpGraph\\Image\\' => 24,
            'Amenadiel\\JpGraph\\Graph\\Tick\\' => 29,
            'Amenadiel\\JpGraph\\Graph\\Scale\\' => 30,
            'Amenadiel\\JpGraph\\Graph\\Axis\\' => 29,
            'Amenadiel\\JpGraph\\Graph\\' => 24,
            'Amenadiel\\JpGraph\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'intercalcus\\fe\\displayTypes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/frontend/displayTypes',
        ),
        'intercalcus\\fe\\' => 
        array (
            0 => __DIR__ . '/../..' . '/frontend',
        ),
        'intercalcus\\db\\model\\' => 
        array (
            0 => __DIR__ . '/../..' . '/database/model',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'CustomJwtAuth\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt',
        ),
        'Amenadiel\\JpGraph\\Util\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/util',
        ),
        'Amenadiel\\JpGraph\\Themes\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/themes',
        ),
        'Amenadiel\\JpGraph\\Text\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/text',
        ),
        'Amenadiel\\JpGraph\\Plot\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/plot',
        ),
        'Amenadiel\\JpGraph\\Image\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/image',
        ),
        'Amenadiel\\JpGraph\\Graph\\Tick\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/graph/tick',
        ),
        'Amenadiel\\JpGraph\\Graph\\Scale\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/graph/scale',
        ),
        'Amenadiel\\JpGraph\\Graph\\Axis\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/graph/axis',
        ),
        'Amenadiel\\JpGraph\\Graph\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src/graph',
        ),
        'Amenadiel\\JpGraph\\' => 
        array (
            0 => __DIR__ . '/..' . '/amenadiel/jpgraph/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit21f7f30ffb95241948b10b86129b96ce::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit21f7f30ffb95241948b10b86129b96ce::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit21f7f30ffb95241948b10b86129b96ce::$classMap;

        }, null, ClassLoader::class);
    }
}