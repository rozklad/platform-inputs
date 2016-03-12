<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\InputType;
use Platform\Attributes\Types\TypeInterface;

class DropzoneType extends FileType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'dropzone';
}
