<?php

namespace BVN\Storage;

use Doctrine\ORM\EntityManagerInterface;

class StorageClient
{
    /** @var EntityManagerInterface */
    protected $storage;

    public function __construct(EntityManagerInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getStorage(): EntityManagerInterface
    {
        return $this->storage;
    }
}