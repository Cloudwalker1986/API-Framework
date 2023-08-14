<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

use ApiCore\Database\Enum\ForeignKeyAction;
use ApiCore\Database\Enum\LazyLoadingCollectionStrategy;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_PROPERTY)]
class OneToMany extends OneToOne
{
    public function __construct(
        private readonly LazyLoadingCollectionStrategy $strategy,
        string $targetFqcn,
        string $targetColumn,
        ForeignKeyAction $onDelete = ForeignKeyAction::CASCADE,
        ForeignKeyAction $onUpdate = ForeignKeyAction::CASCADE,
    ) {
        parent::__construct($targetFqcn, $targetColumn, $onDelete, $onUpdate);
    }

    public function getStrategy(): LazyLoadingCollectionStrategy
    {
        return $this->strategy;
    }
}
