<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\InputType;
use Platform\Attributes\Types\TypeInterface;

class RepeaterType extends BaseType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'repeater';
}
