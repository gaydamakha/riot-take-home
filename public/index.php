<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Error\Renderers\JsonErrorRenderer;
use Slim\Factory\AppFactory;

require __DIR__ . '/../app/bootstrap.php';
/** @var ContainerInterface $container */
/** @var App $app */
AppFactory::setContainer($container);
$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorRenderer = new class() extends JsonErrorRenderer {
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $response = json_decode(parent::__invoke($exception, $displayErrorDetails));
        $response->error = $exception->getMessage();
        return (string)json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
};
$errorMiddleware = $app->addErrorMiddleware(
    displayErrorDetails: $container->get(Staging::class) !== Staging::PROD,
    logErrors: true,
    logErrorDetails: $container->get(Staging::class) !== Staging::PROD,
);
$errorMiddleware->getDefaultErrorHandler()->forceContentType('application/json');
$errorMiddleware->getDefaultErrorHandler()->registerErrorRenderer('application/json', $errorRenderer);

require ROOT_PATH . '/app/routes.php';

$app->run();
