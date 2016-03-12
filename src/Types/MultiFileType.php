<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\InputType;
use Platform\Attributes\Types\TypeInterface;

class MultiFileType extends BaseType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'multifile';

    protected $allowOptions = true;
}
