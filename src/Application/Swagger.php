<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Application;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '0.1.0',
    title: 'riot-take-home',
    attachables: [new OA\Attachable()]
)]
#[OA\Server(
    url: "http://0.0.0.0:9999",
    description: "Localhost"
)]
class Swagger
{
}
