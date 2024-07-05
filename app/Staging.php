<?php

declare(strict_types=1);

enum Staging: string
{
    case LOCAL = 'local';
    case TEST = 'test';
    case PROD = 'prod';
}
