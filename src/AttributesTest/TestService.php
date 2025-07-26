<?php

declare(strict_types = 1);

namespace App\AttributesTest;

use App\AttributesTest\Preprocessors\TrimStringProcessor;

class TestService extends AutoPreprocessingFacade
{
    public function processText(
      #[Preprocessor(TrimStringProcessor::class)]
      string $text,
    ): array {
        return [
          'processedText' => $text,
        ];
    }

    public function saveToDB(array $strings): array
    {
        $toSaveArray = [];
        foreach ($strings as $str) {
            $toSaveArray[] = $this->processText($str);
        }

        return $toSaveArray;
    }
}