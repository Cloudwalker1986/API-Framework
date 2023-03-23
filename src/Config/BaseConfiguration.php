<?php

declare(strict_types=1);

namespace ApiCore\Config;

use ApiCore\Dependency\Container;
use ApiCore\Dependency\Hook\AfterConstruct\Attribute\AfterConstruct;

abstract class BaseConfiguration
{
    private static BaseConfiguration $instance;

    protected array $config;

    abstract public function write(): void;

    public function get(string $key): mixed
    {
        $value = null;
        $pathLevelList = explode('.', $key);
        $max = count($pathLevelList) - 1;
        $iterator = $this->config;
        for ($i = 0; $i <= $max; $i++) {
            if (isset($iterator[$pathLevelList[$i]])) {
                if (is_array($iterator[$pathLevelList[$i]])) {
                    $iterator = $iterator[$pathLevelList[$i]];
                    $value = $iterator;
                } else {
                    $value = $iterator[$pathLevelList[$i]];
                }
            } else {
                $value = null;
            }
        }
        return $value;
    }

    public static function getInstance(): BaseConfiguration
    {
        return self::$instance ?? self::$instance = new static();
    }

    #[AfterConstruct]
    public function storeOnDependencyContainer(): void
    {
        Container::getInstance()->set(self::class, $this);
    }
}
