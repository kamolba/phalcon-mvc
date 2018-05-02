<?php

return new \Phalcon\Config([
    'page_cache_secs' => 20,
    'loginThrottleDecaySeconds' => ONE_MINUTE_IN_SECOND,
    'authentication' => [
        'tokenAgeSeconds' => ONE_DAY_IN_SECOND,
        'jwtLeewaySeconds' => ONE_MINUTE_IN_SECOND,
        'algorithm' => env('ALGORITHM'),
        'secretKey' => env('SECRET'),
    ],
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'phalcon-mvc_db',
        'port'        => '3306',
        'username'    => 'root',
        'password'    => env('DB_PASSWORD'),
        'dbname'      => 'mainDb',
        'charset'     => 'utf8',
    ],
    'redisSession' => [
        'uniqueId' => 'phalcon-mvc',
        'host' => 'redis',
        'port' => 6379,
        'auth' => env('REDIS_AUTH'),
        'persistent' => false,
        'lifetime' => ONE_DAY_IN_SECOND,
        'prefix' => 'phalcon-mvc_session_',
        'index' => 6, // redis db no.
    ],
    'redisBackend' => [
        'host' => 'redis',
        'port' => 6379,
        'auth' => env('REDIS_AUTH'),
        'persistent' => false,
        'prefix' => 'phalcon-mvc_backend_',
        'index' => 7, // redis db no.
        'frontCacheLifetime' => ONE_DAY_IN_SECOND,
    ],
    'application' => [
        'appDir'         => app_path() . DIRECTORY_SEPARATOR,
        'controllersDir' => app_path('controllers') . DIRECTORY_SEPARATOR,
        'modelsDir'      => app_path('models') . DIRECTORY_SEPARATOR,
        'viewsDir'       => app_path('views') . DIRECTORY_SEPARATOR,
        'libraryDir'     => app_path('library') . DIRECTORY_SEPARATOR,
        'cacheDir'       => base_path('cache') . DIRECTORY_SEPARATOR,
        'vendorDir'      => base_path('vendor') . DIRECTORY_SEPARATOR,

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => '/',
    ],
]);
