<?php

declare(strict_types = 1);

namespace App\AttributesTest;

class ArgumentPreprocessor
{
    public static function processMethodArguments(object $object, string $methodName, array $arguments): array
    {
        $reflectionMethod = new \ReflectionMethod($object, $methodName);
        $parameters       = $reflectionMethod->getParameters();

        $processedArguments = [];

        foreach ($parameters as $index => $parameter) {
            $value = $arguments[$index] ?? null;

            // Получаем атрибуты Preprocess для параметра
            $attributes = $parameter->getAttributes(Preprocessor::class);

            if (empty($attributes)) {
                $processedArguments[] = $value;
                continue;
            }

            // Обрабатываем каждый атрибут (может быть несколько)
            foreach ($attributes as $attribute) {
                /** @var Preprocessor $preprocess */
                $preprocess = $attribute->newInstance();

                // Создаем экземпляр процессора
                if (!class_exists($preprocess->processorClass)) {
                    throw new \RuntimeException("Processor class {$preprocess->processorClass} not found");
                }

                $processor = new ($preprocess->processorClass)();

                if (!$processor instanceof ArgumentProcessorInterface) {
                    throw new \RuntimeException('Processor must implement ArgumentProcessorInterface');
                }

                // Обрабатываем значение
                $value = $processor->process($value);
            }

            $processedArguments[] = $value;
        }

        return $processedArguments;
    }

    public static function callWithProcessedArguments(object $object, string $methodName, array $arguments): mixed
    {
        $processedArguments = self::processMethodArguments($object, $methodName, $arguments);

        return $object->$methodName(...$processedArguments);
    }
}