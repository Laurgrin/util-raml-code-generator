<?php
declare(strict_types=1);

namespace Paysera\Bundle\CodeGeneratorBundle\Service\TypeDefinitionBuilder;

use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\ResultTypeDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Exception\InvalidDefinitionException;
use Paysera\Bundle\CodeGeneratorBundle\Service\PropertyTypeResolver;

class ResultTypeBuilder implements TypeDefinitionBuilderInterface
{
    const ANNOTATION_ENTITY = '(entity_type)';

    private $propertyTypeResolver;

    public function __construct(PropertyTypeResolver $propertyTypeResolver)
    {
        $this->propertyTypeResolver = $propertyTypeResolver;
    }

    public function supports(string $name, array $definition): bool
    {
        return strpos($name, 'Result') !== false && !array_key_exists(self::ANNOTATION_ENTITY, $definition);
    }

    public function buildTypeDefinition(string $name, array $definition)
    {
        $type = new ResultTypeDefinition();
        $type
            ->setName($name)
            ->setType(isset($definition['type']) ? $definition['type'] : null)
            ->setDisplayName(isset($definition['displayName']) ? $definition['displayName'] : null)
        ;

        if (empty($definition['properties'])) {
            throw new InvalidDefinitionException('ResultType definition must contain "properties" list');
        }

        $possibleKeys = array_diff(array_keys($definition['properties']), ['_metadata']);
        $itemsType = null;
        $dataKey = null;
        if (count($possibleKeys) > 0) {
            $dataKey = $possibleKeys[0];
            $itemsType = $this->propertyTypeResolver->getArrayItemsType($definition['properties'][$dataKey]);
        }

        $type
            ->setDataKey($dataKey)
            ->setItemsType($itemsType)
            ->setData($definition)
        ;

        return $type;
    }
}
