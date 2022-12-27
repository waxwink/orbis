<?php

namespace Waxwink\Orbis\Framework\Console;

use Throwable;
use Waxwink\Orbis\Contracts\ExceptionHandlerInterface;

class ExceptionHandler implements ExceptionHandlerInterface
{
    protected bool $debug = true;

    /**
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    public function handle(Throwable $exception): void
    {
        //
    }
}
