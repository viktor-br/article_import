<?php

namespace BVN\Import;

use BVN\Entity\ArticleCollection;

interface ImporterInterface
{
    /**
     * @return \BVN\Entity\ArticleCollection
     */
    public function read():ArticleCollection;

    /**
     * @param \BVN\Entity\ArticleCollection $articles
     */
    public function write(ArticleCollection $articles);

    /**
     * @param \BVN\Entity\ArticleCollection $articles
     */
    public function writeSucceeded(ArticleCollection $articles);

    /**
     * @param ArticleCollection $articles
     */
    public function writeFailed(ArticleCollection $articles);

}