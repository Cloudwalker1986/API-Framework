<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

use ApiCore\Database\Exception\EntityNotFoundException;
use ApiCore\Database\Exception\NoEntityTagException;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Repository
{
    public function __construct(
        private readonly string $entityFqcn
    ) {
        $this->validateEntity();
    }

    private function validateEntity(): void
    {
        if (!class_exists($this->entityFqcn)) {
            throw new EntityNotFoundException($this->entityFqcn);
        }

        $reflectionClass = new \ReflectionClass($this->entityFqcn);

        $entity = $reflectionClass->getAttributes(Entity::class);

        if (empty($entity)) {
            throw new NoEntityTagException($this->entityFqcn);
        }
    }
}
