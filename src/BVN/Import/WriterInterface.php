<?php

namespace BVN\Import;

use BVN\Entity\ArticleCollection;

interface WriterInterface
{
    /**
     * @param \BVN\Entity\ArticleCollection $article
     */
    public function write(ArticleCollection $article);
}