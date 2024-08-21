<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Mapping;

use Fusonic\CsvReader\Attributes\IndexMapping;
use Fusonic\CsvReader\Attributes\MappingAttribute;
use Fusonic\CsvReader\Attributes\TitleMapping;
use Fusonic\CsvReader\Exceptions\MappingException;

/**
 * @internal
 */
final class MappingBuilder
{
    /**
     * @param array<int, string>|null $header
     * @param class-string            $className
     *
     * @return MappingInfo[]
     */
    public function build(?array $header, string $className): array
    {
        $class = new \ReflectionClass($className);

        $mapping = [];

        // Properties
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (($index = $this->getColumnIndexFromAttribute($property, $header)) === null) {
                continue;
            }

            $mapping[] = new PropertyMappingInfo((string) $property->getType(), $property->getName(), $index);
        }

        // Methods
        foreach ($class->getMethods(\ReflectionProperty::IS_PUBLIC) as $method) {
            if (($index = $this->getColumnIndexFromAttribute($method, $header)) === null) {
                continue;
            }

            $mapping[] = new MethodMappingInfo((string) $method->getParameters()[0]->getType(), $method->getName(), $index);
        }

        return $mapping;
    }

    /**
     * @param array<int, string>|null $header
     */
    private function getColumnIndexFromAttribute(\ReflectionProperty|\ReflectionMethod $target, ?array $header): ?int
    {
        // First find mapping attribute for this target

        $attributes = $target->getAttributes(MappingAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

        if (0 === \count($attributes)) {
            return null;
        }

        if (\count($attributes) > 1) {
            throw new MappingException(\sprintf('Multiple mapping attributes found on %s::%s.', $target->getDeclaringClass()->getName(), $target->getName()), MappingException::MULTIPLE_MAPPING_ATTRIBUTES_FOUND);
        }

        $attribute = $attributes[0]->newInstance();

        // Now process attribute

        if ($attribute instanceof IndexMapping) {
            return $attribute->getIndex();
        } elseif ($attribute instanceof TitleMapping) {
            if (null === $header) {
                throw new MappingException('CSV has no header row. So using TitleAttribute is not valid.', MappingException::MISSING_HEADER_ROW);
            }

            $index = array_search($attribute->getTitle(), $header, true);

            if (false === $index) {
                throw new MappingException(\sprintf('Column with title "%s" not found in CSV.', $attribute->getTitle()), MappingException::COLUMN_NOT_FOUND);
            }

            return $index;
        }

        throw new MappingException(\sprintf('Mapping attribute of type "%s" not supported.', $attribute::class), MappingException::UNSUPPORTED_MAPPING_ATTRIBUTE);
    }
}
