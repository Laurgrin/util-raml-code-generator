<?php
declare(strict_types=1);

namespace Tests\CodeGeneratorBundle\Exception;

use Paysera\Bundle\CodeGeneratorBundle\Exception\UnrecognizedTypeException;
use PHPUnit\Framework\TestCase;

class UnrecognizedTypeExceptionTest extends TestCase
{
    public function testUndefinedTypeQuotesTheTypeAsDeclared()
    {
        $this->assertSame(
            'Did not found defined type "boolean | nil"',
            UnrecognizedTypeException::undefinedType('boolean | nil')->getMessage()
        );
    }
}
