<?php

namespace App\DataTransferObjects;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * DataTransferObject
 */
abstract class AbstractDataTransferObject
{
    public static function builder(): static
    {
        return new static();
    }

    /**
     * Fills the data transfer object`s properties.
     *
     * @param  array<string, mixed>  $properties
     *
     * @throws ReflectionException
     */
    public function fill(array $properties): AbstractDataTransferObject
    {
        foreach ($properties as $propertyName => $propertyValue) {
            $propertyName = Str::camel($propertyName);
            if (method_exists($this, 'set'.ucfirst($propertyName))) {
                if (
                    (new ReflectionProperty($this, $propertyName))->getType()->getName() === Carbon::class
                ) {
                    if (! empty($propertyValue)) {
                        $propertyValue = Carbon::parse($propertyValue);
                    }
                } elseif (
                    enum_exists((new ReflectionProperty($this, $propertyName))->getType()->getName()) &&
                    ! is_object($propertyValue)
                ) {
                    $propertyValue = (new ReflectionProperty($this, $propertyName))->getType()->getName()::getTryFrom(
                        $propertyValue
                    );
                }
                $this->{'set'.ucfirst($propertyName)}($propertyValue);
            }
        }

        return $this;
    }

    /**
     * Transforms data transfer object to array.
     */
    public function toArray(array $only = [], array $except = [], bool $snake = false): array
    {
        $properties = (new ReflectionClass($this))->getProperties();
        $array = [];
        foreach ($properties as $property) {
            if (! empty($only) && ! in_array($property->getName(), $only)) {
                continue;
            }

            if (! empty($except) && in_array($property->getName(), $except)) {
                continue;
            }

            $value = $property->getValue($this);
            if ($value instanceof AbstractDataTransferObject || $value instanceof Collection) {
                $value = $value->toArray($only, $except, $snake);
            }elseif (is_array($value)) {
                foreach ($value as &$item) {
                    if ($item instanceof AbstractDataTransferObject) {
                        $item = $item->toArray($only, $except, $snake);
                    }
                }
            }
            $propertyName = $snake ? Str::snake($property->getName()) : $property->getName();
            $array[$propertyName] = $value;
        }

        return $array;
    }

    /**
     * Transforms data transfer object to model array.
     *
     * @param  array<int, string>  $only
     * @param  array<int, string>  $except
     * @return array<string, mixed>
     */
    public function toModelArray(array $only = [], array $except = [], $nullable = true): array
    {
        $array = $this->toArray(only: $only, except: $except, snake: true);
        $modelArray = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = new Collection($value);
            }

            if (! $nullable && empty($value)) {
                continue;
            }
            $modelArray[Str::snake($key)] = $value;
        }

        return $modelArray;
    }
}
