<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita6c4097c5b3b2f7933c0fa4ee7e1e9d8
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Medoo\\' => 6,
        ),
        'H' => 
        array (
            'Hashids\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Medoo\\' => 
        array (
            0 => __DIR__ . '/..' . '/catfan/medoo/src',
        ),
        'Hashids\\' => 
        array (
            0 => __DIR__ . '/..' . '/hashids/hashids/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita6c4097c5b3b2f7933c0fa4ee7e1e9d8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita6c4097c5b3b2f7933c0fa4ee7e1e9d8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita6c4097c5b3b2f7933c0fa4ee7e1e9d8::$classMap;

        }, null, ClassLoader::class);
    }
}
