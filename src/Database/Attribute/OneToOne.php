<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

use ApiCore\Database\Enum\ForeignKeyAction;
use ApiCore\Database\Exception\EntityNotFoundException;
use ApiCore\Database\Exception\NoColumnTargetTagException;
use ApiCore\Database\Exception\NoEntityTagException;
use ReflectionClass;
use ReflectionException;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_PROPERTY)]
class OneToOne
{
    /**
     * @throws ReflectionException
     */
    public function __construct(
        private readonly string $targetFqcn,
        private readonly string $targetColumn,
        private readonly ForeignKeyAction $onDelete = ForeignKeyAction::CASCADE,
        private readonly ForeignKeyAction $onUpdate = ForeignKeyAction::CASCADE
    ) {
        $this->validateTargetEntity();
        $this->validateTargetColumn();
    }

    public function getTargetFqcn(): string
    {
        return $this->targetFqcn;
    }

    public function getColumn(): string
    {
        return $this->targetColumn;
    }

    private function validateTargetEntity(): void
    {
        if (!class_exists($this->getTargetFqcn())) {
            throw new EntityNotFoundException($this->getTargetFqcn());
        }

        $reflectionClass = new \ReflectionClass($this->getTargetFqcn());

        $entityAttribute  = $reflectionClass->getAttributes(Entity::class)[0] ?? null;

        if (empty($entityAttribute)) {
            throw new NoEntityTagException($this->getTargetFqcn());
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function validateTargetColumn(): void
    {
        $targetReflection = new ReflectionClass($this->targetFqcn);

        $hasProperty = $targetReflection->hasProperty($this->targetFqcn);

        if (!$hasProperty) {
            foreach ($targetReflection->getProperties() as $property) {
                if ($property->getName() === $this->targetColumn) {
                    $hasProperty = true;
                    break;
                }
            }
        }

        if (!$hasProperty) {
            throw new NoColumnTargetTagException($this->targetFqcn, $this->targetColumn);
        }
    }

    public function getOnDelete(): ForeignKeyAction
    {
        return $this->onDelete;
    }

    public function getOnUpdate(): ForeignKeyAction
    {
        return $this->onUpdate;
    }

    public function getTargetColumn(): string
    {
        return $this->targetColumn;
    }
}
