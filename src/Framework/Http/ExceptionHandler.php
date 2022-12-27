<?php

namespace Waxwink\Orbis\Framework\Http;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Waxwink\Orbis\Configuration\ConfigurationInterface;
use Waxwink\Orbis\Contracts\ExceptionHandlerInterface;
use Throwable;

class ExceptionHandler implements ExceptionHandlerInterface
{
    protected bool $debug;

    public function __construct(protected LoggerInterface $logger, protected ConfigurationInterface $configuration)
    {
        $this->debug = $this->configuration->isDebug();
    }


    public function handle(Throwable $exception): void
    {
        $this->logger->error($exception->getMessage());
        $response = $this->defaultExceptionResponse($exception);
        $response->send();
    }

    /**
     * @throws \JsonException
     */
    protected function defaultExceptionResponse(Throwable $exception): Response
    {
        return new Response(json_encode([
            "message" => $exception->getMessage(),
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "trace" => $exception->getTrace()
        ], JSON_THROW_ON_ERROR), 500, ['Content-Type' => 'application/json']);
    }
}
