<?php

declare(strict_types=1);

namespace ApiCore\Database\DataObject;

use ApiCore\Database\BaseRepository;
use ApiCore\Database\Enum\LazyLoadingCollectionStrategy;
use ApiCore\Database\Hydrator\HydrateEntity;
use Closure;

class LazyLoadingEntity
{
    private bool $initialized = false;

    public function __construct(
        Closure $loader,
        private readonly HydrateEntity $hydrateEntity,
        private readonly string $fqcnEntity,
        private readonly BaseRepository $baseRepository,
        private readonly LazyLoadingCollectionStrategy $lazyLoadingStrategy,
        private readonly string|int $parentId
    ) {

    }


    private function load()
    {

    }
}
