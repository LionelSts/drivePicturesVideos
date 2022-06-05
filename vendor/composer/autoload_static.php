<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5ced395dbf9b42e3b7a4c67c9e8bf696
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Contracts\\Service\\' => 26,
            'Symfony\\Contracts\\Cache\\' => 24,
            'Symfony\\Component\\VarExporter\\' => 30,
            'Symfony\\Component\\Process\\' => 26,
            'Symfony\\Component\\Cache\\' => 24,
            'Spatie\\TemporaryDirectory\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Container\\' => 14,
            'Psr\\Cache\\' => 10,
        ),
        'F' => 
        array (
            'FFMpeg\\' => 7,
        ),
        'A' => 
        array (
            'Alchemy\\BinaryDriver\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Contracts\\Service\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/service-contracts',
        ),
        'Symfony\\Contracts\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/cache-contracts',
        ),
        'Symfony\\Component\\VarExporter\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/var-exporter',
        ),
        'Symfony\\Component\\Process\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/process',
        ),
        'Symfony\\Component\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/cache',
        ),
        'Spatie\\TemporaryDirectory\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/temporary-directory/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'FFMpeg\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-ffmpeg/php-ffmpeg/src/FFMpeg',
        ),
        'Alchemy\\BinaryDriver\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-ffmpeg/php-ffmpeg/src/Alchemy/BinaryDriver',
        ),
    );

    public static $prefixesPsr0 = array (
        'E' => 
        array (
            'Evenement' => 
            array (
                0 => __DIR__ . '/..' . '/evenement/evenement/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        '�' => __DIR__ . '/..' . '/symfony/cache/Traits/ValueWrapper.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5ced395dbf9b42e3b7a4c67c9e8bf696::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5ced395dbf9b42e3b7a4c67c9e8bf696::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit5ced395dbf9b42e3b7a4c67c9e8bf696::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit5ced395dbf9b42e3b7a4c67c9e8bf696::$classMap;

        }, null, ClassLoader::class);
    }
}
