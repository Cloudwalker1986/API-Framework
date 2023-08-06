<?php

declare(strict_types=1);

namespace ApiCore\Database\Result;

class Statement
{
    public function __construct(
        private readonly ?object $entity,
        private readonly int $latestId,
        private readonly bool $operation,
        private readonly string $lastError
    )
    {
    }

    public function getEntity(): ?object
    {
        return $this->entity;
    }

    public function getLatestId(): int
    {
        return $this->latestId;
    }

    public function operationSuccess(): bool
    {
        return $this->operation;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }
}
