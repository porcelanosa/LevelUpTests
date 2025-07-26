<?php
trait ReflectionTrait
{
    /** @var array<string,\ReflectionObject> */
    private array $cash = [];

    protected function setPropertyValue(object $object, string $property, $value): void
    {
        $reflectionObject = $this->getReflectionObject($object);
        $reflectionProperty = $reflectionObject->getProperty($property);
        $reflectionProperty->setValue($object, $value);
    }

    protected function getPropertyValue(object $object, string $property): mixed
    {
        $reflectionObject = $this->getReflectionObject($object);
        $reflectionProperty = $reflectionObject->getProperty($property);

        return $reflectionProperty->getValue($object);
    }

    protected function getReflectionObject(object $object): \ReflectionObject
    {
        if (!method_exists($object, 'getId')) {
            return new \ReflectionObject($object);
        }

        $hash = $object::class . $object->getId();
        if (isset($this->cash[$hash])) {
            return $this->cash[$hash];
        }

        $reflectionObject = new \ReflectionObject($object);
        $this->cash[$hash] = $reflectionObject;

        return $reflectionObject;
    }

    protected function callPrivateMethod(object $object, string $method, mixed ...$args): void
    {
        $reflectionObject = $this->getReflectionObject($object);
        $reflectionMethod = $reflectionObject->getMethod($method);
        $reflectionMethod->invoke($object, ...$args);
    }




    /**
     * @return bool
     *
     * @param  string  $property  свойство класса, которое будем менять
     * @param  mixed   $value     объект, массив и т.д. устанавливаемое значение
     */
    public function setProperty(string $property, mixed $value): bool
    {
        try {
            $objectOrClassName = $this;
            if (!is_object($objectOrClassName)) {
                return false;
            }
            $reflectionClass = new ReflectionClass($objectOrClassName);

            if (!$reflectionClass->hasProperty($property)) {
                return false;
            }

            $property = $reflectionClass->getProperty($property);
            if ($property->hasType()) {
                $propertyType = $property->getType();
                $typeName     = $propertyType->getName();
                $isValidType  = false;

                if ($propertyType->getName()==='mixed' || ($propertyType->allowsNull() && $value===null)) {
                    $isValidType = true;
                } else {
                    $isValidType = match ($typeName) {
                        'int' => is_int($value),
                        'float' => is_float($value) || is_int($value),
                        'string' => is_string($value),
                        'bool' => is_bool($value),
                        'array' => is_array($value),
                        'object' => is_object($value),
                        'callable' => is_callable($value),
                        'iterable' => is_iterable($value),
                        default => $value instanceof $typeName,
                    };
                }
            }

            var_dump($value, $value instanceof $typeName, $typeName);
            if (!$isValidType) {
                return false;
            }
            var_dump($isValidType);

            $property->setAccessible(true);

            if ($property->isStatic()) {
                $property->setValue(null, $value);
            } else {
                $property->setValue($objectOrClassName, $value);
            }

            return true;
        } catch (ReflectionException) {
            return false;
        }
    }

}