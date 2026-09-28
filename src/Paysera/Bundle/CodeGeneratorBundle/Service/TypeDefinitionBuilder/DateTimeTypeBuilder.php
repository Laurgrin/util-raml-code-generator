<?php

namespace Paysera\Bundle\CodeGeneratorBundle\Service\TypeDefinitionBuilder;

use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\DateTimeTypeDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\PropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Service\PropertyTypeResolver;

class DateTimeTypeBuilder implements TypeDefinitionBuilderInterface
{
    private $propertyTypeResolver;

    public function __construct(PropertyTypeResolver $propertyTypeResolver)
    {
        $this->propertyTypeResolver = $propertyTypeResolver;
    }

    public function supports(string $name, array $definition): bool
    {
        $fields = [];
        if (isset($definition['properties'])) {
            $fields = $definition['properties'];
        } elseif (isset($definition['queryParameters'])) {
            $fields = $definition['queryParameters'];
        }
        foreach ($fields as $field) {
            $type = $this->propertyTypeResolver->resolveType($field);
            if (
                (
                    $type !== null
                    && in_array($type, DateTimeTypeDefinition::$supportedTypes, true)
                )
                ||
                (
                    $type === PropertyDefinition::TYPE_INTEGER
                    && array_key_exists(DateTimeTypeDefinition::ANNOTATION_TIMESTAMP, $field)
                )
            ) {
                return true;
            }
        }

        return false;
    }

    public function buildTypeDefinition(string $name, array $definition)
    {
        return (new DateTimeTypeDefinition())
            ->setName(DateTimeTypeDefinition::NAME)
        ;
    }
}
