<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd345dfaceaf0f295f51e5c5b34c1ac45
{
    public static $prefixLengthsPsr4 = array (
        'Y' => 
        array (
            'YannSoaz\\YsCpt\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'YannSoaz\\YsCpt\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd345dfaceaf0f295f51e5c5b34c1ac45::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd345dfaceaf0f295f51e5c5b34c1ac45::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitd345dfaceaf0f295f51e5c5b34c1ac45::$classMap;

        }, null, ClassLoader::class);
    }
}