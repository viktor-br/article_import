<?php

namespace BVN\Elasticsearch;

use BVN\Entity\Article;
use BVN\Entity\ArticleCollection;
use BVN\Language\LanguageDetectionAdapter;
use Elasticsearch\Client;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    public function testWrite()
    {
        $clientMock = $this->createMock(Client::class);
        $loggerMock = $this->createMock(Logger::class);

        $articleRu1 = new Article();
        $articleRu1->setAuthor('Test Author');
        $articleRu1->setExternalId('12345');
        $articleRu1->setId('123');
        $articleRu1->setTitle('Test title');
        $articleRu1->setUrl('http://localhost/ru');
        $articleRu1->setAddedAt(new \DateTime());
        $articleRu1->setCreatedAt(new \DateTime());
        $articleRu1->setUpdatedAt(new \DateTime());
        $articleRu1->setParagraphs([
            "Текст по-русски",
            "Второй параграф и тоже по-русски"
        ]);
        $clientMock->expects($this->at(0))
            ->method('bulk')
            ->with($this->createRequestFromArticle('ru', $articleRu1));

        $articleUk1 = new Article();
        $articleUk1->setAuthor('Test Author');
        $articleUk1->setExternalId('12345');
        $articleUk1->setId('123');
        $articleUk1->setTitle('Test title');
        $articleUk1->setUrl('http://localhost/uk');
        $articleUk1->setAddedAt(new \DateTime());
        $articleUk1->setCreatedAt(new \DateTime());
        $articleUk1->setUpdatedAt(new \DateTime());
        $articleUk1->setParagraphs([
            "Текст українською",
            "Другий параграф українською"
        ]);

        $clientMock->expects($this->at(1))
            ->method('bulk')
            ->with($this->createRequestFromArticle('uk', $articleUk1));

        $languageDetector = new LanguageDetectionAdapter();

        $articles = new ArticleCollection();
        $articles->append($articleRu1);
        $articles->append($articleUk1);

        $writer = new Writer($clientMock, $languageDetector, $loggerMock);
        $writer->write($articles);
    }

    /**
     * @param string $lang
     * @param Article $article
     * @return array
     */
    protected function createRequestFromArticle(string $lang, Article $article)
    {
        return [
            'body' => [
                [
                    'index' => [
                        '_index' => 'article_' . $lang,
                        '_type' => '_doc',
                    ]
                ],
                [
                    'external_id' => $article->getExternalId(),
                    'url' => $article->getUrl(),
                    'author' => $article->getAuthor(),
                    'title' => $article->getTitle(),
                    'paragraphs' => $article->getParagraphs(),
                    'added_at' => $article->getAddedAt()->format('Y-m-d H:i:s'),
                    'created_at' => $article->getCreatedAt()->format('Y-m-d H:i:s')
                ]
            ]
        ];
    }
}
