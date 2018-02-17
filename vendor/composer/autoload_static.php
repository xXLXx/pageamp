<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit619d0140888650e83b23f3c1c1120a8f
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Workerman\\' => 10,
        ),
        'P' => 
        array (
            'PHPSocketIO\\' => 12,
        ),
        'I' => 
        array (
            'IronWorker\\' => 11,
            'IronCore\\' => 9,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
        'C' => 
        array (
            'Channel\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Workerman\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/workerman',
        ),
        'PHPSocketIO\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/phpsocket.io/src',
        ),
        'IronWorker\\' => 
        array (
            0 => __DIR__ . '/..' . '/iron-io/iron_worker/src',
        ),
        'IronCore\\' => 
        array (
            0 => __DIR__ . '/..' . '/iron-io/iron_core/src',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
        'Channel\\' => 
        array (
            0 => __DIR__ . '/..' . '/workerman/channel/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit619d0140888650e83b23f3c1c1120a8f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit619d0140888650e83b23f3c1c1120a8f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}