<?php

namespace BVN\Import;

class ImportRunner
{
    /** @var ImporterInterface */
    protected $importer;

    public function __construct(ImporterInterface $importer)
    {
        $this->importer = $importer;
    }

    public function import(): void
    {
        while (true) {
            $articles = $this->importer->read();

            if (count($articles) === 0) {
                return;
            }

            try {
                $this->importer->write($articles);
                $this->importer->writeSucceeded($articles);
            } catch (\Exception $ex) {
                $this->importer->writeFailed($articles);
                // TODO process exception properly (log)
            }
        }
    }
}