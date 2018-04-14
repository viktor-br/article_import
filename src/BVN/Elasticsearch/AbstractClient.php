<?php

namespace BVN\Elasticsearch;

use Elasticsearch\Client;

abstract class AbstractClient
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
