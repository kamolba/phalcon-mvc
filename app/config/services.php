<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Redis;
use Phalcon\Cache\Backend\Redis as BackendCache;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Dispatcher;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher\Exception as DispatchException;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Cache\Frontend\Output as FrontendCache;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    $environment = env('ENV') ?? 'prod';
    return include app_path() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $environment . '-config.php';
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});

//Set the views cache service
$di->set('viewCache', function () {
    $config = $this->getConfig();
    // Cache data for 1 hour by default
    $frontCache = new FrontendCache(['lifetime' => 3600]);
    $cache = new BackendCache($frontCache, [
        'host' => $config->redisBackend->host,
        'port' => $config->redisBackend->port,
        'auth' => $config->redisBackend->auth,
        'persistent' => $config->redisBackend->persistent,
        'prefix' => 'view_cache_',
        'index' => $config->redisBackend->index,
    ]);
    return $cache;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'port'     => $config->database->port,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    $config = $this->getConfig();
    $session = new Redis([
            'uniqueId' => $config->redisSession->uniqueId,
            'host' => $config->redisSession->host,
            'port' => $config->redisSession->port,
            'auth' => $config->redisSession->auth,
            'persistent' => $config->redisSession->persistent,
            'lifetime' => $config->redisSession->lifetime,
            'prefix' => $config->redisSession->prefix,
            'index' => $config->redisSession->index,
        ]);
    $session->start();
    return $session;
});

$di->setShared('cache', function () {
    $config = $this->getConfig();
    $frontCache = new \Phalcon\Cache\Frontend\Json(array(
        'lifetime' => $config->redisBackend->frontCacheLifetime
    ));
    $cache = new BackendCache($frontCache, [
        'host' => $config->redisBackend->host,
        'port' => $config->redisBackend->port,
        'auth' => $config->redisBackend->auth,
        'persistent' => $config->redisBackend->persistent,
        'prefix' => $config->redisBackend->prefix,
        'index' => $config->redisBackend->index,
    ]);
    return $cache;
});

$di->setShared('userCache', function () {
    $config = $this->getConfig();
    $frontCache = new \Phalcon\Cache\Frontend\Json(array(
        'lifetime' => $config->redisBackend->frontCacheLifetime
    ));
    $cache = new BackendCache($frontCache, [
        'host' => $config->redisBackend->host,
        'port' => $config->redisBackend->port,
        'auth' => $config->redisBackend->auth,
        'persistent' => $config->redisBackend->persistent,
        'prefix' => '',
        'index' => USER_REDIS_DB_INDEX,
    ]);
    return $cache;
});

$di->setShared(
    'dispatcher',
    function () {
        // Create an EventsManager
        $eventsManager = new EventsManager();

        // Attach a listener
        $eventsManager->attach(
            'dispatch:beforeException',
            function (Event $event, $dispatcher, Exception $exception) {
                // Handle 404 exceptions
                if ($exception instanceof DispatchException) {
                    $dispatcher->forward(
                        [
                            'controller' => 'index',
                            'action'     => 'route404',
                        ]
                    );

                    return false;
                }

                // Alternative way, controller or action doesn't exist
                switch ($exception->getCode()) {
                    case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                    case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                        $dispatcher->forward(
                            [
                                'controller' => 'index',
                                'action'     => 'route404',
                            ]
                        );

                        return false;
                }
            }
        );

        $dispatcher = new MvcDispatcher();

        // Bind the EventsManager to the dispatcher
        $dispatcher->setEventsManager($eventsManager);

        return $dispatcher;
    }
);

$di->setShared('logger', function () {
    return new FileLogger(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' .DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . date('Y-m-d').'.log');
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flashSession', function () {
    return new FlashSession([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});

// Set the models cache service
$di->set('modelsCache', function () {
    $config = $this->getConfig();
    // Default 5 mins
    $frontCache = new \Phalcon\Cache\Frontend\Data(['lifetime' => 300]);
    $cache = new BackendCache($frontCache, [
        'host' => $config->redisBackend->host,
        'port' => $config->redisBackend->port,
        'auth' => $config->redisBackend->auth,
        'persistent' => $config->redisBackend->persistent,
        'prefix' => 'model_',
        'index' => $config->redisBackend->index,
    ]);
    return $cache;
});

$di->setShared('timeMaster', function () {
    return new \Carbon\Carbon();
});
