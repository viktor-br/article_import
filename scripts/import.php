#!/usr/bin/env php
<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use BVN\Command\ElasticsearchImport;
use BVN\Command\ElasticsearchClear;
use BVN\Config\ConfigLoader;
use BVN\Elasticsearch\Cleaner;
use BVN\Language\LanguageDetectionAdapter;
use BVN\Sqlite\Resetter;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Application;
use BVN\Command\SqliteReset;
use BVN\Storage\StorageClient;

try {
    $config = (new ConfigLoader())
        ->load([__DIR__.'/../config'], ['config.yml']);
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    exit(1);
}

try {
    $containerBuilder = new ContainerBuilder();
    $containerBuilder->useAutowiring(false);
    $containerBuilder->useAnnotations(false);
    $container = $containerBuilder->build();

    $container->set(StorageClient::class, function () use ($config) {
        $doctrineConfig = Setup::createXMLMetadataConfiguration([__DIR__ . '/../config/doctrine'], true);
        $entityManager = EntityManager::create(
            [
                'driver' => $config['storage']['driver'],
                'path' => $config['storage']['path'],
            ],
            $doctrineConfig
        );

        return new StorageClient($entityManager);
    });
    $container->set(Resetter::class, function () use ($container) {
        $storageClient = $container->get(StorageClient::class);

        return new Resetter($storageClient);
    });
    $container->set(Client::class, function () {
        return ClientBuilder::create()->build();
    });
    $container->set(Cleaner::class, function () use ($container) {
        return new Cleaner($container->get(Client::class));
    });
    $container->set(LanguageDetectionAdapter::class, function () {
        return new LanguageDetectionAdapter();
    });

    $app = new Application('Article importer', 'v0.1.0');
    $app -> add(new ElasticsearchImport('elasticsearch:import', $container));
    $app -> add(new ElasticsearchClear('elasticsearch:clear', $container));
    $app -> add(new SqliteReset('sqlite:reset', $container));
    $app -> run();
} catch (Exception $e) {
    echo $e->getMessage(), "\n";
}

