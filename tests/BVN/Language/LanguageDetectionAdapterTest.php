<?php
declare(strict_types = 1);

namespace BVN\Language;

use PHPUnit\Framework\TestCase;

class LanguageDetectionAdapterTest extends TestCase
{
    public function testDetectUk()
    {
        $languageDetector = new LanguageDetectionAdapter();
        $this->assertEquals('uk', $languageDetector->detect("Текст українською"));
    }

    public function testDetectRu()
    {
        $languageDetector = new LanguageDetectionAdapter();
        $this->assertEquals('ru', $languageDetector->detect("Текст, написаный по-русски"));
    }
}
