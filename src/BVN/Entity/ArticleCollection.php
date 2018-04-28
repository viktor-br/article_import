<?php

namespace BVN\Entity;

class ArticleCollection extends \ArrayObject
{
    public function offsetSet($key, $val) {
        if (!$val instanceof Article) {
            throw new \InvalidArgumentException('Value must be an Article');
        }

        parent::offsetSet($key, $val);
    }
}
