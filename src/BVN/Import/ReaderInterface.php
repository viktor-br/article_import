<?php

namespace BVN\Import;

use BVN\Entity\ArticleCollection;

interface ReaderInterface
{
    /**
     * @return ArticleCollection
     */
    public function read(): ArticleCollection;

    /**
     * @param \BVN\Entity\ArticleCollection $articles
     */
    public function notifySuccess(ArticleCollection $articles): void;

    /**
     * @param ArticleCollection $articles
     */
    public function notifyFailed(ArticleCollection $articles): void;
}