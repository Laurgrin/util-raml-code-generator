<?php
declare(strict_types=1);

namespace Paysera\Bundle\CodeGeneratorBundle\Service;

use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\ArrayPropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\DateTimePropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\DateTimeTypeDefinition;
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
        $declaredType = isset($definition['type']) ? $definition['type'] : null;
        $nullableType = $this->propertyTypeResolver->resolveNullableType($definition);
        $nullable = $nullableType !== null;
        $resolvedType = $nullable ? $nullableType : $declaredType;

        $property = $this->getPropertyDefinition($resolvedType, $definition);

        $property
            ->setName($name)
            ->setType($resolvedType)
            ->setDeclaredType($declaredType)
            ->setDescription(isset($definition['description']) ? $definition['description'] : null)
            ->setRequired(isset($definition['required']) ? $definition['required'] : false)
            ->setNullable($nullable)
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
            $property->setConstants($this->constantBuilder->build($name, $definition['enum']));
        }

        return $property;
    }

    private function getPropertyDefinition(?string $type, array $definition) : PropertyDefinition
    {
        $property = new PropertyDefinition();

        if ($type === PropertyDefinition::TYPE_ARRAY) {
            $property = new ArrayPropertyDefinition();
            $property->setItemsType($this->propertyTypeResolver->getArrayItemsType($definition));
        } elseif (
            $type !== null
            && in_array($type, DateTimeTypeDefinition::$supportedTypes, true)
            || (
                $type === PropertyDefinition::TYPE_INTEGER
                && array_key_exists(DateTimeTypeDefinition::ANNOTATION_TIMESTAMP, $definition)
            )
        ) {
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
