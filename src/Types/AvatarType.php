<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\InputType;
use Platform\Attributes\Types\TypeInterface;

class AvatarType extends BaseType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'avatar';
}
