<?php
declare(strict_types = 1);

namespace BVN\Elasticsearch;

use Elasticsearch\Client;
use PHPUnit\Framework\TestCase;

class CleanerTest extends TestCase
{
    public function testClean()
    {
        $client = $this->createMock(Client::class);
        $client
            ->expects($this->at(0))
            ->method('deleteByQuery')
            ->with([
                'index' => 'article_ru',
                'type' => '_doc',
                'body' => [
                    'query' => [
                        'match_all' => (object)[]
                    ]
                ]
            ]);
        $client
            ->expects($this->at(1))
            ->method('deleteByQuery')
            ->with([
                'index' => 'article_uk',
                'type' => '_doc',
                'body' => [
                    'query' => [
                        'match_all' => (object)[]
                    ]
                ]
            ]);

        $cleaner = new Cleaner($client);
        $cleaner->clean();
    }
}
