<?php

namespace BVN\Elasticsearch;

class Cleaner extends AbstractClient
{
    public function clean()
    {
        foreach (['ru', 'uk'] as $lang) {
            $response = $this->client->deleteByQuery([
                'index' => 'article_' . $lang,
                'type' => '_doc',
                'body' => [
                    'query' => [
                        'match_all' => (object)[]
                    ]
                ]
            ]);
            // TODO handle $response
        }
    }

}