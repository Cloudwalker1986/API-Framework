<?php

declare(strict_types=1);

namespace ApiCore\Database\Hydrator;

use ApiCore\Database\Attribute\Column;
use ApiCore\Database\Attribute\Entity;
use ApiCore\Database\Attribute\ManyToOne;
use ApiCore\Database\Attribute\OneToMany;
use ApiCore\Database\Attribute\OneToOne;
use ApiCore\Database\BaseRepository;
use ApiCore\Database\DataObject\LazyLoadingCollection;
use ApiCore\Database\Exception\ClassNoTaggedAsEntityException;
use ApiCore\Database\Hydrator\Exception\UnkownValueForParameterException;
use ApiCore\Logger\LoggerInterface;
use ApiCore\Utils\StdClassMethod;
use DateTimeImmutable;
use ReflectionClass;
use ReflectionProperty;

class HydrateEntity
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function hydrate(string $fqcnEntity, array $dbRow, BaseRepository $repository): ?object
    {
        $entity = null;
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

        $normalizedData = $this->normalizeData($entityReflection, $dbRow, $repository, $entity);

        if (StdClassMethod::hasConstructor($entityReflection)) {
            $entity = $this->hydrateByConstructor($entityReflection, $normalizedData);
        } else {
            $entity = $entityReflection->newInstance();

            $this->hydrateBySetters($entity, $entityReflection, $normalizedData);
        }

        return $entity;
    }

    private function normalizeData(
        ReflectionClass $entityReflection,
        array $dbRow,
        BaseRepository $repository
    ): array
    {
        $normalizedValues = [];
        foreach ($entityReflection->getProperties() as $property) {
            $columnAttr = $property->getAttributes(Column::class)[0] ?? null;

            if ($columnAttr === null) {
                $normalizedValues = $this->createLazyLoadingReference($property, $repository, $normalizedValues);
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
                    $value = $normalizedData[$parameter->getName()] ?? null;

                    if (!empty($value)) {
                        if ($parameter->getType()->getName() === \DateTimeInterface::class) {
                            $arguments[$parameter->getPosition()] = new \DateTimeImmutable($value);
                        } else {
                            $arguments[$parameter->getPosition()] = $value;
                        }
                    }
                }
                if (empty($arguments)) {
                    continue;
                }
                call_user_func_array([$instance, $method], $arguments);
            }
        }
    }

    private function createLazyLoadingReference(
        ReflectionProperty $property,
        BaseRepository $repository,
        array $normalizedValues
    ): array
    {
        foreach ($property->getAttributes() as $attr) {
            $attribute = $attr->newInstance();

            if ($attribute instanceof OneToOne) {
                $entityAttr = $property->getDeclaringClass()->getAttributes(Entity::class)[0];
                /** @var Entity $entity */
                $entity = $entityAttr->newInstance();
                $referenceKey = sprintf('fk_%s', $entity->getTableName());

                $targetClass = new ReflectionClass($attribute->getTargetFqcn());
                $targetTable = $targetClass->getAttributes(Entity::class)[0]->newInstance()->getTableName();

                if ($attribute instanceof OneToMany) {
                    $collection = new LazyLoadingCollection(
                        function (int $limit, int $offset, int|string $parentId) use ($repository, $referenceKey, $targetTable) {
                            return $repository->getAdapter()->fetchAll(
                                sprintf('SELECT * FROM %s WHERE %s = :reference LIMIT :limit OFFSET :offset', $targetTable, $referenceKey),
                                ['reference' => $parentId, 'limit' => $limit, 'offset' => $offset]
                            );
                        },
                        $this,
                        $attribute->getTargetFqcn(),
                        new class($repository->getAdapter()) extends BaseRepository {
                        },
                        $attribute->getStrategy(),
                        $normalizedValues['id']
                    );
                    $normalizedValues[$property->getName()] = $collection;
                    continue;
                }
            } elseif ($attribute instanceof ManyToOne) {

                $ds = DIRECTORY_SEPARATOR;

                $path = __DIR__ . $ds . '..' . $ds . 'Generator' . $ds . 'Skeletons' . $ds;

                $skeleton = file_get_contents($path . 'SingleEntity.txt');
                $hash = substr(
                    md5($property->getType()->getName()),
                    0,
                    15
                );
                $class = str_replace(
                    [
                        '__HASH__',
                        '__PARENT__',
                    ],
                    [
                        $hash,
                        $property->getType()->getName(),
                    ],
                    $skeleton
                );

                $this->generateBodyForLazyLoading();

                $filePath = __DIR__ . $ds . '..'  . $ds . 'Tmp' . $ds . 'Generated' . $hash . 'Entity.php';

                file_put_contents($filePath, $class);
            }
        }
        return $normalizedValues;

    }


    private function generateBodyForLazyLoading()
    {
        return new class() {
            private function load()
            {
                function (int|string $parentId) use ($repository, $referenceKey, $targetTable) {
                    return $repository->getAdapter()->fetchAll(
                        sprintf('SELECT * FROM %s WHERE %s = :reference', $targetTable, $referenceKey),
                        ['reference' => $parentIdt]
                    );
                },
            }
        }
    }
}
