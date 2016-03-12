<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\InputType;
use Platform\Attributes\Types\TypeInterface;

class VideoType extends BaseType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'video';
}
