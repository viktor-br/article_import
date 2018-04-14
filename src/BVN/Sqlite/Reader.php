<?php

namespace BVN\Sqlite;

use BVN\Import\ReaderInterface;
use BVN\Entity\ArticleCollection;
use BVN\Storage\StorageClient;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class Reader implements ReaderInterface
{
    /** @var StorageClient */
    protected $client;

    /** @var int */
    protected $limit = 10;

    /** @var int */
    protected $counter = 0;

    /** @var int */
    protected $maxResults = 0;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param StorageClient $client
     * @param int $limit
     * @param int $maxResults
     * @param LoggerInterface $logger
     */
    public function __construct(StorageClient $client, int $limit, int $maxResults, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->limit = $limit;
        $this->maxResults = $maxResults;
        $this->logger = $logger;
    }

    /**
     * @return \BVN\Entity\ArticleCollection
     */
    public function read(): ArticleCollection
    {
        $articleCollection = new ArticleCollection();

        if ($this->maxResults > 0 && $this->counter >= $this->maxResults) {
            return $articleCollection;
        }

        $articleRepository = $this->client->getStorage()->getRepository('BVN\Entity\Article');

        $articles = $articleRepository->findBy(['processed' => 0], ['id' => 'ASC'], $this->limit);
        $this->counter += count($articles);

        foreach ($articles as $article) {
            $articleCollection->append($article);
        }

        return $articleCollection;
    }

    /**
     * @param ArticleCollection $articles
     * @throws ORMException
     */
    public function notifySuccess(ArticleCollection $articles): void
    {
        try {
            foreach ($articles as $article) {
                $article->setProcessed(1);
                $this->client->getStorage()->persist($article);
            }
            $this->client->getStorage()->flush();
        } catch (ORMException $e) {
            throw $e;
        }
    }

    /**
     * @param \BVN\Entity\ArticleCollection $articles
     * @throws ORMException
     */
    public function notifyFailed(ArticleCollection $articles): void
    {
        try {
            foreach ($articles as $article) {
                $article->setProcessed(-1);
                $this->client->getStorage()->persist($article);
            }
            $this->client->getStorage()->flush();
        } catch (ORMException $e) {
            throw $e;
        }
    }
}