<?php

namespace Waxwink\Orbis\Queue;

class QueueConnection
{
    public function __construct(protected JobRepository $repository)
    {
    }

    public function addJob(object $job, array $inputs): void
    {
        $this->repository->add(
            serialize($job),
            json_encode(array_map('serialize', $inputs), JSON_THROW_ON_ERROR)
        );
    }

    public function nextJob(): ?QueuePayload
    {
        $item = $this->repository->shift();
        if (!$item) {
            return null;
        }

        $job = unserialize($item["job"]);
        $input = [];
        foreach (json_decode($item["input"], true, 512, JSON_THROW_ON_ERROR) as $serializedInput) {
            $input[] = unserialize($serializedInput);
        }

        return new QueuePayload($job, $input);
    }
}
