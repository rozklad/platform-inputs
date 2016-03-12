<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\InputType;
use Platform\Attributes\Types\TypeInterface;

class WysiwygType extends BaseType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'wysiwyg';
}
