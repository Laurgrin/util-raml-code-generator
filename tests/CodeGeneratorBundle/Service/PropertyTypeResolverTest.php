<?php
declare(strict_types=1);

namespace Tests\CodeGeneratorBundle\Service;

use Paysera\Bundle\CodeGeneratorBundle\Service\PropertyTypeResolver;
use PHPUnit\Framework\TestCase;

class PropertyTypeResolverTest extends TestCase
{
    /**
     * @dataProvider dataProviderTestDefinitionWithoutTypeResolvesToNothing
     */
    public function testDefinitionWithoutTypeResolvesToNothing(array $definition)
    {
        $resolver = new PropertyTypeResolver();

        $this->assertNull($resolver->resolveType($definition));
        $this->assertFalse($resolver->isNullable($definition));
        $this->assertFalse($resolver->isDateTime($definition));
    }

    public function dataProviderTestDefinitionWithoutTypeResolvesToNothing()
    {
        return [
            'empty definition' => [[]],
            'timestamp annotation without a type' => [['(datetime_timestamp)' => null]],
        ];
    }
}
