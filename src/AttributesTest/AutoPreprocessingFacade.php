<?php

declare(strict_types = 1);

namespace App\AttributesTest;

abstract class AutoPreprocessingFacade
{
    public function __call(string $name, array $arguments): mixed
    {
        // Проверяем, существует ли метод
        if (!method_exists($this, $name)) {
            throw new \RuntimeException("Method $name does not exist");
        }

        // Обрабатываем аргументы
        $processedArguments = ArgumentPreprocessor::processMethodArguments(
          $this,
          $name,
          $arguments
        );

        // Вызываем оригинальный метод
        return $this->$name(...$processedArguments);
    }
}