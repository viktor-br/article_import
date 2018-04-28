<?php
declare(strict_types = 1);

namespace BVN\Import;

use BVN\Entity\ArticleCollection;
use PHPUnit\Framework\TestCase;

class BasicTest extends TestCase
{
    public function testBasicImporter()
    {
        $articles = new ArticleCollection();

        $reader = $this->createMock(ReaderInterface::class);
        $reader->expects($this->once())
            ->method('read')
            ->willReturn($articles);
        $reader->expects($this->once())
            ->method('notifySuccess')
            ->willReturn($articles);
        $reader->expects($this->once())
            ->method('notifyFailed')
            ->willReturn($articles);

        $writer = $this->createMock(WriterInterface::class);
        $writer
            ->expects($this->once())
            ->method('write')
            ->with($articles);

        $importer = new Basic($reader, $writer);
        $actualArticles = $importer->read();
        $importer->writeSucceeded($articles);
        $importer->writeFailed($articles);
        $importer->write($articles);

        $this->assertEquals($articles, $actualArticles);
    }
}
