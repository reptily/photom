<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd48a57c0ba30fa62da8fa4d4d25a572f
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Ph\\' => 3,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ph\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd48a57c0ba30fa62da8fa4d4d25a572f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd48a57c0ba30fa62da8fa4d4d25a572f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}