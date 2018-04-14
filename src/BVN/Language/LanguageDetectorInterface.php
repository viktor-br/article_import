<?php

namespace BVN\Language;

interface LanguageDetectorInterface
{
    /**
     * @param string $str
     * @return string
     */
    public function detect(string $str): string;
}