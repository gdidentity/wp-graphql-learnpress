<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9eff18d58666b44872ca2890d795b1f8
{
    public static $files = array (
        '70cc370597fe5b5458c5727352f4391c' => __DIR__ . '/../..' . '/src/Connection/cpt-connection-args.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPGraphQL\\Extensions\\LearnPress\\' => 32,
        ),
        'V' => 
        array (
            'VariableAnalysis\\' => 17,
        ),
        'D' => 
        array (
            'Dealerdirect\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => 55,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPGraphQL\\Extensions\\LearnPress\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'VariableAnalysis\\' => 
        array (
            0 => __DIR__ . '/..' . '/sirbrillig/phpcs-variable-analysis/VariableAnalysis',
        ),
        'Dealerdirect\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => 
        array (
            0 => __DIR__ . '/..' . '/dealerdirect/phpcodesniffer-composer-installer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9eff18d58666b44872ca2890d795b1f8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9eff18d58666b44872ca2890d795b1f8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9eff18d58666b44872ca2890d795b1f8::$classMap;

        }, null, ClassLoader::class);
    }
}
