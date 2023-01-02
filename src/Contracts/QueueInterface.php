<?php

namespace Waxwink\Orbis\Contracts;

interface QueueInterface
{
    public function addJob(Queueable $listener, array $input): void;
}
