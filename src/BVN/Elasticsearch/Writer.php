<?php

namespace BVN\Elasticsearch;

use BVN\Entity\ArticleCollection;
use BVN\Entity\Article;
use BVN\Language\LanguageDetectorInterface;
use BVN\Import\WriterInterface;
use Elasticsearch\Client;
use Psr\Log\LoggerInterface;

class Writer extends AbstractClient implements WriterInterface
{
    /** @var LanguageDetectorInterface */
    protected $languageDetector;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(Client $client, LanguageDetectorInterface $languageDetector, LoggerInterface $logger)
    {
        parent::__construct($client);

        $this->languageDetector = $languageDetector;
        $this->logger = $logger;
    }

    /**
     * @param ArticleCollection $articles
     */
    public function write(ArticleCollection $articles)
    {
        $articlesByLang = [];
        foreach ($articles as $article) {
            /** @var Article $article */
            $lang = $this->languageDetector->detect(implode("\n", $article->getParagraphs()));
            $this->logger->debug(sprintf("Detected language '%s' for article '%s'", $lang, $article->getTitle()));
            if (!isset($articlesByLang[$lang])) {
                $articlesByLang[$lang] = new ArticleCollection();
            }
            $articlesByLang[$lang]->append($article);
        }

        foreach ($articlesByLang as $lang => $articles) {
            /** @var Article $article */
            $params = [
                'body' => []
            ];
            foreach ($articles as $article) {
                $params['body'][] = [
                    'index' => [
                        '_index' => 'article' . '_' . $lang,
                        '_type' => '_doc',
                    ]
                ];
                $params['body'][] = [
                    'external_id' => $article->getExternalId(),
                    'url' => $article->getUrl(),
                    'author' => $article->getAuthor(),
                    'title' => $article->getTitle(),
                    'paragraphs' => $article->getParagraphs(),
                    'added_at' => $article->getAddedAt()->format('Y-m-d H:i:s'),
                    'created_at' => $article->getCreatedAt()->format('Y-m-d H:i:s')
                ];
            }

            $this->client->bulk($params);
        }
    }
}