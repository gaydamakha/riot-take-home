<?php

declare(strict_types=1);

use Gaydamakha\RiotTakeHome\Domain\Encryption\Encryptors\Base64Encryptor;
use Gaydamakha\RiotTakeHome\Domain\Encryption\StringEncryptorInterface;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sentry\Monolog\Handler as SentryMonologHandler;
use Sentry\SentrySdk;
use function DI\get;

return [
    Staging::class => fn() => Staging::from(getenv('STAGING')),

    LoggerInterface::class => function (ContainerInterface $container) {
        $logger = new Logger(getenv('APPLICATION_NAME'));

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        /** @var Staging $staging */
        $staging = $container->get(Staging::class);
        $handler = new StreamHandler(
            'php://stdout',
            $staging === Staging::LOCAL ? Level::Debug : Level::Info,
        );
        $handler->setFormatter(new JsonFormatter());
        $logger->pushHandler($handler);

        $sentryHandler = new SentryMonologHandler(SentrySdk::getCurrentHub(), Level::Warning);
        $logger->pushHandler($sentryHandler);

        return $logger;
    },
    StringEncryptorInterface::class => get(Base64Encryptor::class),
    'signing.key' => getenv('SIGNING_KEY'),
    'signing.algorithm' => getenv('SIGNING_ALGORITHM'),
];
