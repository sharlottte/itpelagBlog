<?php

declare(strict_types=1);

use DI\Container;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return [
    FilesystemLoader::class => static fn () => new FilesystemLoader(__DIR__.'/../templates'),
    Environment::class => static fn (Container $c) => new Environment($c->get(FilesystemLoader::class)),
    Configuration::class => static fn () => ORMSetup::createAttributeMetadataConfiguration(
        paths: [__DIR__.'/../src'],
        isDevMode: true,
    ),
    Connection::class => static fn (Container $c) => DriverManager::getConnection([
        'driver' => $c->get('db_driver'),
        'user' => $c->get('db_username'),
        'password' => $c->get('db_password'),
        'dbname' => $c->get('db_name'),
    ]),
    EntityManager::class => DI\create(EntityManager::class)
        ->constructor(DI\get(Connection::class), DI\get(Configuration::class)),
];
