<?php

declare(strict_types=1);

namespace ApiCore\Database\Enum;

enum LazyLoadingCollectionStrategy: string
{
    case ON_DEMAND_EACH_TIME = 'onDemandEachTime';
    case ONCE_AND_IN_MEMORY = 'onceAndInMemory';

    public function isOnDemandEachTime(): bool
    {
        return $this === self::ON_DEMAND_EACH_TIME;
    }
}
