<?php

namespace BVN\Sqlite;

use BVN\Storage\StorageClient;

class Resetter
{
    /** @var StorageClient */
    protected $client;

    public function __construct(StorageClient $client)
    {
        $this->client = $client;
    }

    public function reset(): void
    {
        $conn = $this->client->getStorage()
            ->getConnection();

        $conn
            ->prepare("UPDATE article SET processed = 0 WHERE processed = 1")
            ->execute();

        $conn
            ->prepare("UPDATE article SET processed = -2 WHERE title = '' OR author = '' OR length(paragraphs) == 4")
            ->execute();

        $conn
            ->prepare("UPDATE article SET created_at = DATETIME(created_at), added_at = DATETIME(added_at)")
            ->execute();
    }
}