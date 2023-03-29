<?php

declare(strict_types=1);

namespace ApiCore\Dependency\Resolver;

use ApiCore\Dependency\Attribute\InterfaceTag;

class InterfaceResolver
{
    public function resolve(\ReflectionParameter $parameter): ?string
    {
        $attribute = $parameter->getAttributes(InterfaceTag::class)[0] ?? null;

        if ($attribute !== null) {
            /** @var InterfaceTag $interfaceTag */
            $interfaceTag = $attribute->newInstance();
            return $interfaceTag->getClassToLoad();
        }
        return null;
    }
}
