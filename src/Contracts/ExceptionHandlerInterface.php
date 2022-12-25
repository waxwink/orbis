<?php

namespace Waxwink\Orbis\Contracts;

use Throwable;

interface ExceptionHandlerInterface
{
    public function handle(Throwable $exception): void;
}
