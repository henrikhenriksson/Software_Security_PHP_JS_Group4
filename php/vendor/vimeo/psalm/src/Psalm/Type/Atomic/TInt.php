<?php
namespace Psalm\Type\Atomic;

class TInt extends Scalar
{
    public function __toString()
    {
        return 'int';
    }

    /**
     * @return string
     */
    public function getKey(bool $include_extra = true)
    {
        return 'int';
    }
}
