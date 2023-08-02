<?php

declare(strict_types=1);

namespace ApiCore\Database;

class FunctionSignature
{
    public function __construct(
        private readonly string $methodName,
        private readonly string $returnParam,
        private readonly string $queryValue,
        private readonly string $parameters,
        private readonly string $parameterVariable,
        private readonly array $parameterVariableArray
    ) {}

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getReturnParam(): string
    {
        return $this->returnParam;
    }

    public function getQueryValue(): string
    {
        return $this->queryValue;
    }

    public function getParameters(): string
    {
        return $this->parameters;
    }

    public function getParameterVariable(): string
    {
        return $this->parameterVariable;
    }

    public function getParameterVariableArray(): array
    {
        return $this->parameterVariableArray;
    }
}
