<?php

declare(strict_types = 1);

namespace App\AttributesTest\Preprocessors;

use App\AttributesTest\ArgumentProcessorInterface;

class TrimStringProcessor implements ArgumentProcessorInterface
{
    public function process(mixed $value): mixed
    {
        if (!is_string($value)) {
           $value = (string)$value;
        }

        return trim($value);
    }
}