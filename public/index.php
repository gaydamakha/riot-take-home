<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

require __DIR__ . '/../app/bootstrap.php';
/** @var ContainerInterface $container */
/** @var App $app */
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(
    displayErrorDetails: $container->get(Staging::class) !== Staging::PROD,
    logErrors: false,
    logErrorDetails: false, // Disable default error logging
);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');

require ROOT_PATH . '/app/routes.php';

$app->run();
