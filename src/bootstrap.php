<?php

use DI\ContainerBuilder;

require_once __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/db.php');


if (file_exists(__DIR__ . '/../config/db.local.php')) {
    $containerBuilder->addDefinitions(__DIR__ . '/../config/db.local.php');
}

$containerBuilder->addDefinitions(__DIR__ . '/../config/di.php');
$container = $containerBuilder->build();

return $container;
