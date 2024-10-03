<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3831819c8c8a3f9d6f84c9c1fd85192a
{
    public static $files = array (
        '95c987c510bf5845a8e27473f7ea87bf' => __DIR__ . '/../..' . '/src/lib/postTypes.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WpStarterPlugin\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WpStarterPlugin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/classes',
            1 => __DIR__ . '/../..' . '/src/classes/*',
            2 => __DIR__ . '/../..' . '/src/classes/*/*',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'WpStarterPlugin\\Base\\Singleton' => __DIR__ . '/../..' . '/src/classes/Base/Singleton.php',
        'WpStarterPlugin\\PostTypes\\ExamplePostType' => __DIR__ . '/../..' . '/src/classes/PostTypes/ExamplePostType.php',
        'WpStarterPlugin\\Settings\\ExampleACFSettingsPage' => __DIR__ . '/../..' . '/src/classes/Settings/ExampleACFSettingsPage.php',
        'WpStarterPlugin\\Settings\\ExampleSettingsPage' => __DIR__ . '/../..' . '/src/classes/Settings/ExampleSettingsPage.php',
        'WpStarterPlugin\\Settings\\ExampleSettingsSubPage' => __DIR__ . '/../..' . '/src/classes/Settings/ExampleSettingsSubPage.php',
        'WpStarterPlugin\\Settings\\SettingsPage' => __DIR__ . '/../..' . '/src/classes/Settings/SettingsPage.php',
        'WpStarterPlugin\\WpStarterPlugin' => __DIR__ . '/../..' . '/src/classes/WpStarterPlugin.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3831819c8c8a3f9d6f84c9c1fd85192a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3831819c8c8a3f9d6f84c9c1fd85192a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3831819c8c8a3f9d6f84c9c1fd85192a::$classMap;

        }, null, ClassLoader::class);
    }
}
