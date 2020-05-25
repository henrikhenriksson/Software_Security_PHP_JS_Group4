<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit52a153953d0362c4ed14a2192108c9be
{
    public static $files = array (
        '094883ee9da9e6fabd95b86a5ef61b72' => __DIR__ . '/..' . '/latitude/latitude/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'ParagonIE\\EasyDB\\' => 17,
            'ParagonIE\\Corner\\' => 17,
        ),
        'L' => 
        array (
            'Latitude\\QueryBuilder\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ParagonIE\\EasyDB\\' => 
        array (
            0 => __DIR__ . '/..' . '/paragonie/easydb/src',
        ),
        'ParagonIE\\Corner\\' => 
        array (
            0 => __DIR__ . '/..' . '/paragonie/corner/src',
        ),
        'Latitude\\QueryBuilder\\' => 
        array (
            0 => __DIR__ . '/..' . '/latitude/latitude/src',
        ),
    );

    public static $classMap = array (
        'Config' => __DIR__ . '/../..' . '/classes/config.class.php',
        'Login' => __DIR__ . '/../..' . '/classes/login.class.php',
        'Member' => __DIR__ . '/../..' . '/classes/member.class.php',
        'Post' => __DIR__ . '/../..' . '/classes/post.class.php',
        'Role' => __DIR__ . '/../..' . '/classes/role.class.php',
        'Session' => __DIR__ . '/../..' . '/classes/session.class.php',
        'SessionAdapter' => __DIR__ . '/../..' . '/classes/session.class.php',
        'TestSession' => __DIR__ . '/../..' . '/classes/session.class.php',
        'Token' => __DIR__ . '/../..' . '/classes/token.class.php',
        'WebSession' => __DIR__ . '/../..' . '/classes/session.class.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit52a153953d0362c4ed14a2192108c9be::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit52a153953d0362c4ed14a2192108c9be::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit52a153953d0362c4ed14a2192108c9be::$classMap;

        }, null, ClassLoader::class);
    }
}
