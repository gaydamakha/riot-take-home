<?php

declare(strict_types=1);

use DI\ContainerBuilder;

require __DIR__ . '/Staging.php';
require __DIR__ . '/constants.php';

require ROOT_PATH . '/vendor/autoload.php';

date_default_timezone_set('UTC');

// For local environment only
if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . '.env')) {
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(ROOT_PATH);
    $dotenv->load();
}

$staging = Staging::from(getenv('STAGING'));

if ($staging !== Staging::LOCAL && getenv('SENTRY_DSN') !== false) {
    Sentry\init([
        'dsn' => getenv('SENTRY_DSN'),
        'environment' => $staging->value,
    ]);
}

$builder = new ContainerBuilder();

if ($staging === Staging::PROD) {
    $builder->enableCompilation(ROOT_PATH . '/temp');
    $builder->writeProxiesToFile(true, ROOT_PATH . '/temp/proxies');
}

$builder->useAttributes(true);

$builder->addDefinitions(ROOT_PATH . '/app/dependencies.php');

try {
    $container = $builder->build();
} catch (Exception $e) {
    die("Cannot build container: {$e->getMessage()}");
}
