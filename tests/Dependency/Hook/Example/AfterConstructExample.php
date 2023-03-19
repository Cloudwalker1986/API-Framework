<?php

declare(strict_types=1);

namespace ApiCoreTest\Dependency\Hook\Example;

use ApiCore\Dependency\Hook\AfterConstruct\Attribute\AfterConstruct;

class AfterConstructExample
{
    private ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    #[AfterConstruct]
    public function afterConstruct(): void
    {
        $this->value = 'Changed after Construct hook';
    }
}
