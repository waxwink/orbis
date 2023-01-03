<?php

namespace Waxwink\Orbis\Queue;

class QueuePayload
{
    protected object $job;

    protected array $inputs = [];

    /**
     * @param object $job
     * @param array $inputs
     */
    public function __construct(object $job, array $inputs = [])
    {
        $this->job = $job;
        $this->inputs = $inputs;
    }

    /**
     * @return object
     */
    public function getJob(): object
    {
        return $this->job;
    }

    /**
     * @return array
     */
    public function getInputs(): array
    {
        return $this->inputs;
    }
}
