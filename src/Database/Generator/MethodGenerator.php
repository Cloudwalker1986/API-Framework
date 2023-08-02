<?php

declare(strict_types=1);

namespace ApiCore\Database\Generator;

use ApiCore\Database\Attribute\Query;
use ApiCore\Database\FunctionSignature;
use ReflectionAttribute;
use ReflectionMethod;

class MethodGenerator
{
    private const KEY_ORDER_DIRECTION = 'orderDirection';
    private const SEARCH_FOR_COLLECTION = 'searchForCollection';
    private const SEARCH_FOR_ONE = 'searchForOne';

    private array $reservedTypes = [
        "array",
        "string",
        "int",
        "bool",
        "float",
        "object",
        "stdClass",
        "void",
        'null'
    ];

    private array $methodForCriteriaSearch = ['findOneByCriteriaOrNull', 'findByCriteria'];

    private array $methodForCrd = ['persists', 'delete'];

    /**
     * Generate the whole function of a given method.
     */
    public function generate(ReflectionMethod $method, ?ReflectionAttribute $queryAttribute, array &$use): string
    {
        $returnType = $method->getReturnType()?->getName();
        if ((!in_array($returnType, $this->reservedTypes, true)) && !in_array($returnType, $use, true)) {
            $use[] = $returnType;
        }
        [$parameters, $parameterVariable, $parameterVariableArray, $use] = $this->resolveParameters($method, $use);

        $returnNameExploded = explode('\\', $returnType);

        if ($queryAttribute === null) {
            $functionSignature = new FunctionSignature(
                $method->getName(),
                end($returnNameExploded),
                '',
                mb_substr($parameters, 0, -1),
                mb_substr($parameterVariable, 0, -1),
                $parameterVariableArray
            );
            if ((in_array($method->getName(), $this->methodForCriteriaSearch, true))) {
                return $this->getFunctionTemplateForCriteria($functionSignature);
            } elseif (in_array($method->getName(), $this->methodForCrd, true)) {
                return $this->getFunctionTemplateForCud($functionSignature);
            }
        }

        /** @var Query $query */
        $query = $queryAttribute->newInstance();
        $functionSignature = new FunctionSignature(
            $method->getName(),
            ($method->getReturnType()?->allowsNull() ? '?' : '') . end($returnNameExploded),
            $query->getQuery(),
            mb_substr($parameters, 0, -1),
            mb_substr($parameterVariable, 0, -1),
            $parameterVariableArray
        );

        return $this->getFunctionTemplate($functionSignature);
    }

    /**
     * Resolve the parameters of a function and
     */
    private function resolveParameters(ReflectionMethod $method, array $use): array
    {
        $parameters = '';
        $parameterVariable = '';
        $parameterVariableArray = [];
        foreach ($method->getParameters() as $parameter) {
            $parameterKey = $parameter->getType();
            if (!in_array($parameter->getType(), $this->reservedTypes, true)) {
                $name = $parameter->getType()?->getName();

                if (!in_array($name, $use, true) && !in_array($name, $this->reservedTypes, true)) {
                    $use[] = $name;
                }
                $parameterObject = explode('\\', $parameter->getType()?->getName());
                $parameterKey = end($parameterObject);
            }
            $parameters .= sprintf(
                '%s $%s,',
                $parameter->getType()?->allowsNull() ? '?' . $parameterKey : $parameterKey,
                $parameter->getName()
            );

            $parameterVariable .= '\'' . lcfirst($parameter->getName()) . '\' => $' . $parameter->getName() . ',';
            $parameterVariableArray[lcfirst($parameterKey)] = '$' . $parameter->getName();
        }

        return [$parameters, $parameterVariable, $parameterVariableArray, $use];
    }

    /**
     * Generates a function body based on the Query attribute and the function paramters of the interface.
     */
    private function getFunctionTemplate(FunctionSignature $functionSignature): string
    {
        if (!str_contains('Collection', $functionSignature->getReturnParam())) {
            return sprintf('        
        public function %2$s(%3$s): %4$s 
        {
            return $this->handleQuerySingleEntity(\'%1$s\', [' . $functionSignature->getParameterVariable() . ']);
        }
        ',
                    $functionSignature->getQueryValue(),
                    $functionSignature->getMethodName(),
                    $functionSignature->getParameters(),
                    $functionSignature->getReturnParam()
                )
                . PHP_EOL;
        }
        return sprintf('
        public function %2$s(%3$s): %4$s 
        {
            return $this->handleQueryMultipleEntities(\'%1$s\', [' . $functionSignature->getParameterVariable() . ']);
        }',
                $functionSignature->getQueryValue(),
                $functionSignature->getMethodName(),
                $functionSignature->getParameters(),
                $functionSignature->getReturnParam()
            ) . PHP_EOL;


    }

    /**
     * Generates a function body for reading a concrete entity or entity collection.
     */
    private function getFunctionTemplateForCriteria(FunctionSignature $functionSignature): string
    {
        return sprintf('        
        public function %1$s(%2$s): %3$s 
        {
            return $this->%4$s(' . implode(', ', $functionSignature->getParameterVariableArray()) . ');
        }
        ',
                $functionSignature->getMethodName(),
                $functionSignature->getParameters(),
                $functionSignature->getReturnParam(),
                array_key_exists(self::KEY_ORDER_DIRECTION, $functionSignature->getParameterVariableArray())
                    ? self::SEARCH_FOR_COLLECTION
                    : self::SEARCH_FOR_ONE
            )
            . PHP_EOL;
    }

    /**
     * Generates a function body for Create update & delete.
     */
    private function getFunctionTemplateForCud(FunctionSignature $functionSignature): string
    {
        return sprintf('        
        public function %1$s(%2$s): %3$s 
        {
            $this->%4$s(' . implode(', ', $functionSignature->getParameterVariableArray()) . ');
        }
        ',
                $functionSignature->getMethodName(),
                $functionSignature->getParameters(),
                $functionSignature->getReturnParam(),
                $functionSignature->getMethodName() === 'persists'
                    ? 'createOrUpdate'
                    : 'remove'
            )
            . PHP_EOL;
    }
}
