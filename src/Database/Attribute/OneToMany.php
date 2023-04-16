<?php

declare(strict_types=1);

namespace ApiCore\Database\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE|\Attribute::TARGET_PROPERTY)]
class OneToMany extends OneToOne
{

}
