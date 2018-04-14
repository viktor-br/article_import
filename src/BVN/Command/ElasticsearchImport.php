<?php

namespace BVN\Command;

use BVN\Elasticsearch\Writer;
use BVN\Import\Basic;
use BVN\Import\ImportRunner;
use BVN\Language\LanguageDetectionAdapter;
use BVN\Sqlite\Reader;
use BVN\Storage\StorageClient;
use Elasticsearch\Client;
use Monolog\Logger;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ElasticsearchImport extends AbstractCommand
{
    public function configure()
    {
        $this
            ->addOption("n", null, InputOption::VALUE_OPTIONAL)
            ->addOption("id", null, InputOption::VALUE_OPTIONAL)
            ->addOption("limit", null, InputOption::VALUE_OPTIONAL);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = (int) $input->getOption('limit');
        $maxResults = (int) $input->getOption('n');

        $reader = new Reader(
            $this->container->get(StorageClient::class),
            $limit,
            $maxResults,
            $this->container->get(Logger::class)
        );
        $writer = new Writer(
            $this->container->get(Client::class),
            $this->container->get(LanguageDetectionAdapter::class),
            $this->container->get(Logger::class)
        );
        $importer = new Basic($reader, $writer);
        $importRunner = new ImportRunner($importer, $this->container->get(Logger::class));
        $importRunner->import();
    }
}