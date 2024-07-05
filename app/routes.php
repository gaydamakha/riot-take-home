<?php

declare(strict_types=1);

use Gaydamakha\RiotTakeHome\Application\Decrypt\DecryptPayloadController;
use Gaydamakha\RiotTakeHome\Application\Encrypt\EncryptPayloadController;
use Gaydamakha\RiotTakeHome\Application\Sign\SignPayloadController;
use Gaydamakha\RiotTakeHome\Application\Verify\VerifyPayloadController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/** @var ContainerInterface $container */
/** @var $app Slim\App */

// Healthcheck controller
$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response): ResponseInterface {
    return $response->withStatus(200);
});

$app->post('/encrypt', EncryptPayloadController::class);
$app->post('/decrypt', DecryptPayloadController::class);
$app->post('/sign', SignPayloadController::class);
$app->post('/verify', VerifyPayloadController::class);
