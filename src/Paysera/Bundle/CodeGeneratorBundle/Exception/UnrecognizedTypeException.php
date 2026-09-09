<?php

namespace Paysera\Bundle\CodeGeneratorBundle\Exception;

class UnrecognizedTypeException extends \Exception
{
    /**
     * @param string $type
     *
     * @return self
     */
    public static function forType($type)
    {
        return new self(sprintf('Did not found defined type "%s"', $type));
    }
}
