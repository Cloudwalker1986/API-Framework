<?php

declare(strict_types=1);

namespace ApiCoreTest\Database\Attribute\Example;

use ApiCore\Database\Attribute\Column;
use ApiCore\Database\Attribute\Entity;
use ApiCore\Database\Attribute\OneToOne;
use ApiCore\Database\Attribute\Primary;
use ApiCore\Database\Enum\PrimaryKeyValue;

#[Entity]
class OneToOneEntityFoo
{
    #[Primary(PrimaryKeyValue::TYPE_INTEGER)]
    #[Column]
    private int $columnA;

    private OneToOneEntityBar $bar;
}
