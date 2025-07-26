<?php

declare(strict_types = 1);

namespace App\AttributesTest;

interface ArgumentProcessorInterface
{
    public function process(mixed $value): mixed;
}