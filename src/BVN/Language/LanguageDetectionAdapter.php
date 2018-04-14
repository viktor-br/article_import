<?php

namespace BVN\Language;

use LanguageDetection\Language;

class LanguageDetectionAdapter implements LanguageDetectorInterface
{
    /** @var Language */
    protected $ld;

    public function __construct()
    {
        $this->ld = new Language(['ru', 'uk']);
    }

    /**
     * @param string $str
     * @return string
     */
    public function detect(string $str): string
    {
        $res = $this->ld->detect($str)->close();

        return key($res);
    }
}
