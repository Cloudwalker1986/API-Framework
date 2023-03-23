<?php

declare(strict_types=1);

namespace ApiCore\Config\Yaml;
use ApiCore\Config\BaseConfiguration;
use ApiCore\Dependency\Hook\AfterConstruct\Attribute\AfterConstruct;
use InvalidArgumentException;

class Configuration extends BaseConfiguration
{
    #[AfterConstruct]
    public function write(): void
    {
        if (!defined('APPLICATION_CONFIG')) {
            throw new InvalidArgumentException(
                'Application configuration constant "APPLICATION_CONFIG" is not defined'
            );
        }

        $config = yaml_parse_file(APPLICATION_CONFIG);
        $devConfig = [];

        if (defined('APPLICATION_DEV_CONFIG') && file_exists(APPLICATION_DEV_CONFIG)) {
            $devConfig = yaml_parse_file(APPLICATION_DEV_CONFIG);
        }

        $this->config = array_merge($config, $devConfig);
    }
}
