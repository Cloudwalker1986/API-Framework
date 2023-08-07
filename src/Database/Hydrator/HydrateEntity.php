<?php

declare(strict_types=1);

namespace ApiCore\Database\Hydrator;

use ApiCore\Database\Attribute\Column;
use ApiCore\Database\Attribute\Entity;
use ApiCore\Database\Exception\ClassNoTaggedAsEntityException;
use ApiCore\Database\Hydrator\Exception\UnkownValueForParameterException;
use ApiCore\Logger\LoggerInterface;
use ApiCore\Utils\Collection;
use ApiCore\Utils\StdClassMethod;
use DateTimeImmutable;
use ReflectionClass;

class HydrateEntity
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function hydrate(string $fqcnEntity, array $dbRow): ?object
    {
        $entityReflection = new ReflectionClass($fqcnEntity);
        $entityAttribute = $entityReflection->getAttributes(Entity::class)[0] ?? null;

        if ($entityAttribute === null) {
            $msg = $fqcnEntity . ' is not an entity and can´t be hydrated.';
            $this->logger->warning($msg);
            throw new ClassNoTaggedAsEntityException($msg);
        }

        if (empty($dbRow)) {
            $msg = 'No data are provided for entity class ' . $fqcnEntity;
            $this->logger->debug($msg);
            return null;
        }

        $normalizedData = $this->normalizeData($entityReflection, $dbRow);

        if (StdClassMethod::hasConstructor($entityReflection)) {
            return $this->hydrateByConstructor($entityReflection, $normalizedData);
        }


        $entity = $entityReflection->newInstance();

        $this->hydrateBySetters($entity, $entityReflection, $normalizedData);

        return $entity;
    }

    private function normalizeData(ReflectionClass $entityReflection, array $dbRow): array
    {
        $normalizedValues = [];
        foreach ($entityReflection->getProperties() as $property) {
            $columnAttr = $property->getAttributes(Column::class)[0] ?? null;

            if ($columnAttr === null) {
                $this->logger->debug($property->getName() . ' is not defined as column and will be skipped.');
                continue;
            }

            /** @var Column $column */
            $column = $columnAttr->newInstance();

            $key = $this->getKeyForProperty($column, $property);

            $normalizedValues[$property->getName()] = $dbRow[$key] ?? null;
        }

        return $normalizedValues;
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function hydrateByConstructor(ReflectionClass $entityReflection, $normalizedData)
    {
        $arguments = [];

        foreach ($entityReflection->getConstructor()?->getParameters() as $parameter) {
            if (!array_key_exists($parameter->getName(), $normalizedData)) {
                throw new UnkownValueForParameterException($parameter->getName());
            }

            $value = $normalizedData[$parameter->getName()];

            if (empty($value) && !$parameter->allowsNull()) {
                throw new UnkownValueForParameterException($parameter->getName());
            }

            $value = match($parameter->getType()->getName()) {
                \DateTimeInterface::class => new DateTimeImmutable($value),
                'int' => (int) $value,
                'float' => (float) $value,
                default => $value
            };

            $arguments[$parameter->getPosition()] = $value;
        }

        return $entityReflection->newInstanceArgs($arguments);
    }

    private function getKeyForProperty(Column $column, \ReflectionProperty $property): string
    {
        if (empty($column->getName())) {
            return mb_strtolower(preg_replace('/\B([A-Z])/', '_$1', $property->getName()));
        }

        return $column->getName();
    }

    private function hydrateBySetters(object $instance, ReflectionClass $entityReflection, array $normalizedData): void
    {
        foreach ($entityReflection->getMethods() as $reflectionMethod) {
            $arguments = [];
            if (StdClassMethod::isSetterMethod($reflectionMethod)) {
                $method = $reflectionMethod->getName();
                foreach ($reflectionMethod->getParameters() as $parameter) {
                    if ($parameter->getType()->getName() === \DateTimeInterface::class) {
                        $arguments[$parameter->getPosition()] = new \DateTimeImmutable($normalizedData[$parameter->getName()]);
                    } else {
                        $arguments[$parameter->getPosition()] = $normalizedData[$parameter->getName()];
                    }
                }
                if (empty($arguments)) {
                    continue;
                }
                call_user_func_array([$instance, $method], $arguments);
            }
        }
    }
}
