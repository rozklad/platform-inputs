<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\TypeInterface;

class TagsType extends BaseType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'tags';
}
