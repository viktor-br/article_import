<?php

namespace BVN\Import;

use Psr\Log\LoggerInterface;

class ImportRunner
{
    /** @var ImporterInterface */
    protected $importer;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(ImporterInterface $importer, LoggerInterface $logger)
    {
        $this->importer = $importer;
        $this->logger = $logger;
    }

    public function import(): void
    {
        $counter = 1;
        while (true) {
            $this->logger->debug(sprintf("#%d Reading articles started", $counter));
            $articles = $this->importer->read();
            $this->logger->debug(sprintf("#%d Reading articles finished (%d articles)", $counter, count($articles)));

            if (count($articles) === 0) {
                return;
            }

            try {
                $this->logger->debug(sprintf("#%d Writing articles started", $counter));
                $this->importer->write($articles);
                $this->logger->debug(sprintf("#%d Writing articles finished (%d articles)", $counter, count($articles)));

                $this->logger->debug(sprintf("#%d Success notification started", $counter));
                $this->importer->writeSucceeded($articles);
                $this->logger->debug(sprintf("#%d Success notification finished (%d articles)", $counter, count($articles)));
            } catch (\Exception $ex) {
                $this->logger->debug(sprintf("#%d Failed notification started", $counter));
                $this->importer->writeFailed($articles);
                $this->logger->debug(sprintf("#%d Failed notification finished (%d articles)", $counter, count($articles)));

                $this->logger->error($ex->getMessage());
            }
            $counter++;
        }
    }
}