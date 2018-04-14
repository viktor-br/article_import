<?php

namespace BVN\Import;

use BVN\Entity\ArticleCollection;

class Basic implements ImporterInterface
{
    /** @var ReaderInterface */
    protected $reader;

    /** @var WriterInterface */
    protected $writer;

    public function __construct(ReaderInterface $reader, WriterInterface $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * @return \BVN\Entity\ArticleCollection
     */
    public function read(): ArticleCollection
    {
        return $this->reader->read();
    }

    /**
     * @param ArticleCollection $articles
     */
    public function write(ArticleCollection $articles): void
    {
        $this->writer->write($articles);
    }

    /**
     * @param ArticleCollection $articles
     */
    public function writeSucceeded(ArticleCollection $articles): void
    {
        $this->reader->notifySuccess($articles);
    }

    /**
     * @param \BVN\Entity\ArticleCollection $articles
     */
    public function writeFailed(ArticleCollection $articles): void
    {
        $this->reader->notifyFailed($articles);
    }
}