<?php

declare(strict_types = 1);

namespace App\AttributesTest;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Preprocessor
{
    public function __construct(
      public string $processorClass
    ) {
    }
}