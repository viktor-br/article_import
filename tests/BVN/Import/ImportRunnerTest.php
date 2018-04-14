<?php

namespace BVN\Import;

use BVN\Entity\ArticleCollection;
use BVN\Entity\Article;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class ImportRunnerTest extends TestCase
{
    public function testImportRunner()
    {
        $importerMock = $this->createMock(ImporterInterface::class);
        $loggerMock = $this->createMock(Logger::class);

        $articles = new ArticleCollection();
        $articles->append(new Article());
        $articles->append(new Article());

        $importerMock
            ->expects($this->at(0))
            ->method('read')
            ->willReturn($articles);

        $importerMock
            ->expects($this->at(1))
            ->method('read')
            ->willReturn(new ArticleCollection());

        $importerMock
            ->expects($this->once())
            ->method('write');

        $importerMock
            ->expects($this->once())
            ->method('writeSucceeded');

        $runner = new ImportRunner($importerMock, $loggerMock);
        $runner->import();
    }

    public function testImportRunnerFailed()
    {
        $importerMock = $this->createMock(ImporterInterface::class);
        $loggerMock = $this->createMock(Logger::class);

        $articles = new ArticleCollection();
        $articles->append(new Article());
        $articles->append(new Article());

        $importerMock
            ->expects($this->at(0))
            ->method('read')
            ->willReturn($articles);

        $importerMock
            ->expects($this->once())
            ->method('write')
            ->willThrowException(new \Exception());

        $importerMock
            ->expects($this->never())
            ->method('writeSucceeded');

        $runner = new ImportRunner($importerMock, $loggerMock);
        $runner->import();
    }
}
