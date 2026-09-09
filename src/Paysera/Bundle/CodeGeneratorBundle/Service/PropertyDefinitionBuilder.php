<?php
declare(strict_types=1);

namespace Paysera\Bundle\CodeGeneratorBundle\Service;

use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\ArrayPropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\DateTimePropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\DateTimeTypeDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\FilePropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\PropertyDefinition;
use Raml\ApiDefinition;
use Raml\Types\NullType;

class PropertyDefinitionBuilder
{
    private const UNION_SEPARATOR = '|';
    private const NULLABLE_UNION_MEMBERS = 2;
    private const NULLABLE_SHORTHAND_SUFFIX = '?';

    private $constantBuilder;

    public function __construct(ConstantBuilder $constantBuilder)
    {
        $this->constantBuilder = $constantBuilder;
    }

    public function buildPropertyDefinition(string $name, array $definition)
    {
        $declaredType = isset($definition['type']) ? $definition['type'] : null;
        $resolvedType = $declaredType;
        $nullable = false;

        if ($declaredType !== null) {
            $unwrappedType = $this->unwrapNullableType($declaredType);
            if ($unwrappedType !== null && $this->isExpressibleType($unwrappedType, $definition)) {
                $resolvedType = $unwrappedType;
                $nullable = true;
            }
        }

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

    private function unwrapNullableType(string $type) : ?string
    {
        $shorthand = $this->unwrapNullableShorthand($type);
        if ($shorthand !== null) {
            return $shorthand;
        }

        if (strpos($type, self::UNION_SEPARATOR) === false) {
            return null;
        }

        $members = array_map(
            'trim',
            explode(self::UNION_SEPARATOR, $type, self::NULLABLE_UNION_MEMBERS + 1)
        );
        if (count($members) !== self::NULLABLE_UNION_MEMBERS || in_array('', $members, true)) {
            return null;
        }

        $nilPositions = array_keys(array_filter($members, [$this, 'declaresNoValue']));
        if (count($nilPositions) !== 1) {
            return null;
        }

        return $members[$nilPositions[0] === 0 ? 1 : 0];
    }

    private function unwrapNullableShorthand(string $type) : ?string
    {
        if (substr($type, -1) !== self::NULLABLE_SHORTHAND_SUFFIX) {
            return null;
        }

        $declaredType = trim(substr($type, 0, -1));

        return $declaredType === '' ? null : $declaredType;
    }

    private function declaresNoValue(string $member) : bool
    {
        return (bool) $member
            && ApiDefinition::determineType($member, ['type' => $member]) instanceof NullType
        ;
    }

    private function isExpressibleType(string $type, array $definition) : bool
    {
        if (strpos($type, '[]') !== false) {
            return false;
        }

        if ($type === PropertyDefinition::TYPE_ARRAY) {
            return $this->hasArrayItems($definition)
                && in_array(
                    $definition['items']['type'],
                    PropertyDefinition::getScalarTypes(),
                    true
                )
            ;
        }

        return true;
    }

    private function hasArrayItems(array $definition) : bool
    {
        return isset($definition['items']['type']);
    }

    private function getPropertyDefinition(?string $type, array $definition) : PropertyDefinition
    {
        $property = new PropertyDefinition();

        if ($type === PropertyDefinition::TYPE_ARRAY) {
            $property = new ArrayPropertyDefinition();
            if ($this->hasArrayItems($definition)) {
                $property->setItemsType($definition['items']['type']);
            }
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
