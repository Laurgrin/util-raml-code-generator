<?php

namespace Paysera\Bundle\CodeGeneratorBundle\Exception;

class UnrecognizedTypeException extends \Exception
{
    public static function undefinedType(string $type) : self
    {
        return new self(sprintf('Did not found defined type "%s"', $type));
    }
}
