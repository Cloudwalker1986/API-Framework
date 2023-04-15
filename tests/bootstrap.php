<?php

use ApiCore\Config\Yaml\Configuration;
use ApiCore\Dependency\Container;

define('APPLICATION_CONFIG', __DIR__ . DIRECTORY_SEPARATOR . 'config.yaml');

Container::getInstance()->get(Configuration::class);
