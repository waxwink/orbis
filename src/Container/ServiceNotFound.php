<?php

namespace Waxwink\Orbis\Container;

use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFound extends \Exception implements NotFoundExceptionInterface
{
}
