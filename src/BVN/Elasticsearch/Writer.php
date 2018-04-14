<?php

namespace BVN\Elasticsearch;

use BVN\Entity\ArticleCollection;
use BVN\Entity\Article;
use BVN\Language\LanguageDetectorInterface;
use BVN\Import\WriterInterface;
use Elasticsearch\Client;

class Writer extends AbstractClient implements WriterInterface
{
    /** @var LanguageDetectorInterface */
    protected $languageDetector;

    public function __construct(Client $client, LanguageDetectorInterface $languageDetector)
    {
        parent::__construct($client);

        $this->languageDetector = $languageDetector;
    }

    /**
     * @param ArticleCollection $articles
     */
    public function write(ArticleCollection $articles)
    {
        $articlesByLang = [];
        foreach ($articles as $article) {
            $lang = $this->languageDetector->detect(implode("\n", $article->getParagraphs()));
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

            $response = $this->client->bulk($params);
            // TODO handle response
        }
    }
}