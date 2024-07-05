<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/** @var ContainerInterface $container */
/** @var $app Slim\App */

// Healthcheck controller
$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
    return $response->withStatus(200);
});
