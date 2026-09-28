<?php
declare(strict_types=1);

namespace Paysera\Bundle\CodeGeneratorBundle\Service;

use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\ArrayPropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\DateTimePropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\FilePropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\PropertyDefinition;

class PropertyDefinitionBuilder
{
    private $constantBuilder;
    private $propertyTypeResolver;

    public function __construct(ConstantBuilder $constantBuilder, PropertyTypeResolver $propertyTypeResolver)
    {
        $this->constantBuilder = $constantBuilder;
        $this->propertyTypeResolver = $propertyTypeResolver;
    }

    public function buildPropertyDefinition(string $name, array $definition)
    {
        $resolvedType = $this->propertyTypeResolver->resolveType($definition);

        $property = $this->getPropertyDefinition($resolvedType, $definition);

        $property
            ->setName($name)
            ->setType($resolvedType)
            ->setDeclaredType(isset($definition['type']) ? $definition['type'] : null)
            ->setDescription(isset($definition['description']) ? $definition['description'] : null)
            ->setRequired(isset($definition['required']) ? $definition['required'] : false)
            ->setNullable($this->propertyTypeResolver->isNullable($definition))
        ;

        if ($resolvedType !== null && strpos($resolvedType, '[]') !== false) {
            $property->setType(PropertyDefinition::TYPE_ARRAY);
        }

        if (
            !in_array(
                $property->getType(),
                array_merge(
                    PropertyDefinition::getSimpleTypes(),
                    [PropertyDefinition::TYPE_FILE]
                ),
                true
            )
        ) {
            $property
                ->setType(PropertyDefinition::TYPE_REFERENCE)
                ->setReference($resolvedType)
            ;
        }

        if (isset($definition['enum'])) {
            $enumValues = array_values(array_filter($definition['enum'], function ($value) {
                return $value !== null;
            }));
            $property->setConstants($this->constantBuilder->build($name, $enumValues));
        }

        return $property;
    }

    private function getPropertyDefinition(?string $type, array $definition) : PropertyDefinition
    {
        $property = new PropertyDefinition();

        if ($type === PropertyDefinition::TYPE_ARRAY) {
            $property = new ArrayPropertyDefinition();
            $property->setItemsType($this->propertyTypeResolver->getArrayItemsType($definition));
        } elseif ($this->propertyTypeResolver->isDateTime($definition)) {
            $property = new DateTimePropertyDefinition();
            if (isset($definition['format'])) {
                $property->setFormat($definition['format']);
            }
        } elseif ($type === PropertyDefinition::TYPE_FILE) {
            $property = new FilePropertyDefinition();
        }

        return $property;
    }
}
