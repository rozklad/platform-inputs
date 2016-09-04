<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\InputType;
use Platform\Attributes\Types\TypeInterface;

class ScaleType extends BaseType implements TypeInterface
{

    public $settings = [

        'min' => [
            'name'      => 'sanatorium/inputs::types.settings.min',
            'default'   => 0,
            'validation'=> 'numeric|max:max',
            'type'      => 'number'
        ],

        'max' => [
            'name'      => 'sanatorium/inputs::types.settings.max',
            'default'   => 100,
            'validation'=> 'numeric|min:min',
            'type'      => 'number',
        ]

    ];

    /**
     * {@inheritDoc}
     */
    protected $identifier = 'scale';
}
