<?php
declare(strict_types=1);

namespace Tests\CodeGeneratorBundle\Service\TypeDefinitionBuilder;

use Paysera\Bundle\CodeGeneratorBundle\Service\PropertyTypeResolver;
use Paysera\Bundle\CodeGeneratorBundle\Service\TypeDefinitionBuilder\ResultTypeBuilder;
use PHPUnit\Framework\TestCase;

class ResultTypeBuilderTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestItemsType
     */
    public function testItemsType($dataProperty, ?string $expectedItemsType)
    {
        $builder = new ResultTypeBuilder(new PropertyTypeResolver());

        $type = $builder->buildTypeDefinition(
            'ItemResult',
            ['properties' => ['items' => $dataProperty, '_metadata' => ['type' => 'object']]]
        );

        $this->assertSame('items', $type->getDataKey());
        $this->assertSame($expectedItemsType, $type->getItemsType());
    }

    public function dataProviderTestItemsType()
    {
        return [
            'items declared as a type map' => [['type' => 'array', 'items' => ['type' => 'Item']], 'Item'],
            'items declared as a shorthand' => [['type' => 'array', 'items' => 'Item'], 'Item'],
            'no items declared' => [['type' => 'array'], null],
            'data property declared with the shorthand form' => ['Item[]', null],
        ];
    }
}
