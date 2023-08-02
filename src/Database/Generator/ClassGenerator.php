<?php

declare(strict_types=1);

namespace ApiCore\Database\Generator;

use ReflectionClass;
use ApiCore\Database\Attribute\Query;
use ApiCore\Database\ReadRepositoryInterface;
use ApiCore\Database\CrudRepositoryInterface;

class ClassGenerator
{

    public function __construct(private readonly MethodGenerator $methodGenerator)
    {
    }

    public function generate(?object $instance, ReflectionClass $reflectionClass): string
    {
        $ds = DIRECTORY_SEPARATOR;

        $className = 'ApiCore\\Database\\Tmp\\Generated%sRepository';

        $skeleton = 'BaseSkeleton.txt';
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'Skeletons' . DIRECTORY_SEPARATOR;

        if (in_array(CrudRepositoryInterface::class, $reflectionClass->getInterfaceNames())) {
            $skeleton = 'CrudSkeleton.txt';
        } elseif (in_array(ReadRepositoryInterface::class, $reflectionClass->getInterfaceNames())) {
            $skeleton = 'ReaderSkeleton.txt';
        }

        $skeleton = file_get_contents($path . $skeleton);

        $use = [$reflectionClass->getName()];

        $name = $reflectionClass->getName();

        $classNameExploded = explode('\\', $name);
        $interfaceName = end($classNameExploded);
        $hash = substr(
            md5($interfaceName),
            0,
            15
        );

        $className = sprintf($className, $hash);
        $filePath = __DIR__ . $ds . '..' . $ds . 'Tmp' . $ds . 'Generated' . $hash . 'Repository.php';

        if (file_exists($filePath)) {
            return $className;
        }

        $bodyContent = $this->renderBodyContent($reflectionClass, $use);

        $uses = '';
        foreach ($use as $u) {
            $uses .= 'use ' . $u . ';' . PHP_EOL;
        }

        $classBody = str_replace(
            [
                '__USE__',
                '__HASH__',
                '__INTERFACE__',
                '__BODY__',
            ],
            [
                $uses,
                $hash,
                $interfaceName,
                $bodyContent,
            ],
            $skeleton
        );

        file_put_contents(
            __DIR__ . $ds . '..' . $ds . 'Tmp' . $ds . 'Generated' . $hash . 'Repository.php',
            $classBody
        );

        return $className;
    }

    private function renderBodyContent(ReflectionClass $interfaceReflection, array &$use): string
    {
        $body = '';

        foreach ($interfaceReflection->getMethods() as $method) {
            $queryAttribute = $method->getAttributes(Query::class)[0] ?? null;

            $body .= $this->methodGenerator->generate($method, $queryAttribute, $use);
        }

        return $body;
    }
}
