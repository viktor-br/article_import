<?php

namespace BVN\Import;

use BVN\Entity\ArticleCollection;
use BVN\Entity\Article;
use PHPUnit\Framework\TestCase;

class ImportRunnerTest extends TestCase
{
    public function testImportRunner()
    {
        $mock = $this->createMock(ImporterInterface::class);

        $articles = new ArticleCollection();
        $articles->append(new Article());
        $articles->append(new Article());

        $mock
            ->expects($this->at(0))
            ->method('read')
            ->willReturn($articles);

        $mock
            ->expects($this->at(1))
            ->method('read')
            ->willReturn(new ArticleCollection());

        $mock
            ->expects($this->once())
            ->method('write');

        $mock
            ->expects($this->once())
            ->method('writeSucceeded');

        $runner = new ImportRunner($mock);
        $runner->import();
    }

    public function testImportRunnerFailed()
    {
        $mock = $this->createMock(ImporterInterface::class);

        $articles = new ArticleCollection();
        $articles->append(new Article());
        $articles->append(new Article());

        $mock
            ->expects($this->at(0))
            ->method('read')
            ->willReturn($articles);

        $mock
            ->expects($this->once())
            ->method('write')
            ->willThrowException(new \Exception());

        $mock
            ->expects($this->never())
            ->method('writeSucceeded');

        $runner = new ImportRunner($mock);
        $runner->import();
    }
}
