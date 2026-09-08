<?php
declare(strict_types=1);

namespace Tests\CodeGeneratorBundle\Service;

use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\ArrayPropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\DateTimePropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Entity\Definition\PropertyDefinition;
use Paysera\Bundle\CodeGeneratorBundle\Service\ConstantBuilder;
use Paysera\Bundle\CodeGeneratorBundle\Service\PropertyDefinitionBuilder;
use PHPUnit\Framework\TestCase;

class PropertyDefinitionBuilderTest extends TestCase
{
    /**
     * @var PropertyDefinitionBuilder
     */
    private $builder;

    protected function setUp(): void
    {
        $this->builder = new PropertyDefinitionBuilder(new ConstantBuilder());
    }

    /**
     * @dataProvider dataProviderTestNullableDeclaration
     */
    public function testNullableDeclaration(
        string $declaredType,
        string $expectedType,
        ?string $expectedReference,
        bool $expectedNullable
    ) {
        $property = $this->builder->buildPropertyDefinition(
            'value',
            ['type' => $declaredType, 'required' => true]
        );

        $this->assertSame($expectedType, $property->getType());
        $this->assertSame($expectedReference, $property->getReference());
        $this->assertSame($expectedNullable, $property->isNullable());
    }

    public function dataProviderTestNullableDeclaration()
    {
        return [
            'plain scalar carries no nullability' => [
                'boolean',
                PropertyDefinition::TYPE_BOOLEAN,
                null,
                false,
            ],
            'scalar before nil' => [
                'boolean | nil',
                PropertyDefinition::TYPE_BOOLEAN,
                null,
                true,
            ],
            'scalar after nil' => [
                'nil | boolean',
                PropertyDefinition::TYPE_BOOLEAN,
                null,
                true,
            ],
            'no surrounding whitespace' => [
                'boolean|nil',
                PropertyDefinition::TYPE_BOOLEAN,
                null,
                true,
            ],
            'padded whitespace' => [
                'boolean   |   nil',
                PropertyDefinition::TYPE_BOOLEAN,
                null,
                true,
            ],
            'named type resolves to the bare reference' => [
                'Owner | nil',
                PropertyDefinition::TYPE_REFERENCE,
                'Owner',
                true,
            ],
            'two genuine types are left intact for the validator to reject' => [
                'string | integer',
                PropertyDefinition::TYPE_REFERENCE,
                'string | integer',
                false,
            ],
            'three members are left intact even when one is nil' => [
                'string | integer | nil',
                PropertyDefinition::TYPE_REFERENCE,
                'string | integer | nil',
                false,
            ],
            'nil unioned with itself declares no type to fall back to' => [
                'nil | nil',
                PropertyDefinition::TYPE_REFERENCE,
                'nil | nil',
                false,
            ],
            'a missing member declares no type to fall back to' => [
                ' | nil',
                PropertyDefinition::TYPE_REFERENCE,
                ' | nil',
                false,
            ],
            'a trailing separator is not a nullable union' => [
                'string |',
                PropertyDefinition::TYPE_REFERENCE,
                'string |',
                false,
            ],
            'a leading separator is not a nullable union' => [
                '| string',
                PropertyDefinition::TYPE_REFERENCE,
                '| string',
                false,
            ],
            'a falsy member is not a nil declaration' => [
                'string | 0',
                PropertyDefinition::TYPE_REFERENCE,
                'string | 0',
                false,
            ],
            'a falsy member is not a nil declaration in either position' => [
                '0 | string',
                PropertyDefinition::TYPE_REFERENCE,
                '0 | string',
                false,
            ],
            'bare nil is not a union' => [
                'nil',
                PropertyDefinition::TYPE_REFERENCE,
                'nil',
                false,
            ],
            'null is the same concept as nil' => [
                'boolean | null',
                PropertyDefinition::TYPE_BOOLEAN,
                null,
                true,
            ],
            'null before the declared type' => [
                'null | Owner',
                PropertyDefinition::TYPE_REFERENCE,
                'Owner',
                true,
            ],
            'nil unioned with null declares no type to fall back to' => [
                'nil | null',
                PropertyDefinition::TYPE_REFERENCE,
                'nil | null',
                false,
            ],
            'shorthand suffix marks a scalar nullable' => [
                'boolean?',
                PropertyDefinition::TYPE_BOOLEAN,
                null,
                true,
            ],
            'shorthand suffix marks a reference nullable' => [
                'Owner?',
                PropertyDefinition::TYPE_REFERENCE,
                'Owner',
                true,
            ],
            'a bare shorthand suffix declares no type' => [
                '?',
                PropertyDefinition::TYPE_REFERENCE,
                '?',
                false,
            ],
            'an array without items is left intact rather than unwrapped' => [
                'array | nil',
                PropertyDefinition::TYPE_REFERENCE,
                'array | nil',
                false,
            ],
            'an array shorthand is not unwrapped' => [
                'string[] | nil',
                PropertyDefinition::TYPE_ARRAY,
                null,
                false,
            ],
            'an undefined type unwraps and is left for the validator to reject' => [
                'Unknown | nil',
                PropertyDefinition::TYPE_REFERENCE,
                'Unknown',
                true,
            ],
        ];
    }

    public function testNullableArrayWithScalarItemsIsUnwrapped()
    {
        $property = $this->builder->buildPropertyDefinition(
            'tags',
            ['type' => 'array | nil', 'items' => ['type' => 'string'], 'required' => true]
        );

        $this->assertSame(PropertyDefinition::TYPE_ARRAY, $property->getType());
        $this->assertTrue($property->isNullable());
    }

    public function testArrayWithoutItemsKeepsItsArrayDefinitionSoTheValidatorRejectsIt()
    {
        $property = $this->builder->buildPropertyDefinition('contents', ['type' => 'array']);

        $this->assertInstanceOf(ArrayPropertyDefinition::class, $property);
        $this->assertNull($property->getItemsType());
    }

    public function testNullableArrayOfReferencesIsLeftForTheValidatorToReject()
    {
        $property = $this->builder->buildPropertyDefinition(
            'owners',
            ['type' => 'array | nil', 'items' => ['type' => 'Owner'], 'required' => true]
        );

        $this->assertSame(PropertyDefinition::TYPE_REFERENCE, $property->getType());
        $this->assertSame('array | nil', $property->getReference());
        $this->assertFalse($property->isNullable());
    }

    public function testNullableDateTimeKeepsItsOwnDefinitionType()
    {
        $property = $this->builder->buildPropertyDefinition(
            'updated_at',
            ['type' => 'datetime | nil', 'required' => true]
        );

        $this->assertInstanceOf(DateTimePropertyDefinition::class, $property);
        $this->assertTrue($property->isNullable());
        $this->assertTrue($property->isRequired());
        $this->assertTrue($property->allowsNullValue());
    }

    /**
     * @dataProvider dataProviderTestPresenceAndNullabilityAreIndependent
     */
    public function testPresenceAndNullabilityAreIndependent(
        string $declaredType,
        bool $required,
        bool $expectedRequired,
        bool $expectedNullable,
        bool $expectedAllowsNullValue
    ) {
        $property = $this->builder->buildPropertyDefinition(
            'value',
            ['type' => $declaredType, 'required' => $required]
        );

        $this->assertSame($expectedRequired, $property->isRequired());
        $this->assertSame($expectedNullable, $property->isNullable());
        $this->assertSame($expectedAllowsNullValue, $property->allowsNullValue());
    }

    public function dataProviderTestPresenceAndNullabilityAreIndependent()
    {
        return [
            'required and not nullable' => ['boolean', true, true, false, false],
            'required and nullable' => ['boolean | nil', true, true, true, true],
            'optional and not nullable' => ['boolean', false, false, false, true],
            'optional and nullable' => ['boolean | nil', false, false, true, true],
        ];
    }

    public function testRequiredFacetSurvivesUnwrapping()
    {
        $property = $this->builder->buildPropertyDefinition(
            'owner',
            ['type' => 'Owner | nil', 'required' => true]
        );

        $this->assertTrue($property->isRequired());
        $this->assertSame('Owner', $property->getReference());
    }
}
