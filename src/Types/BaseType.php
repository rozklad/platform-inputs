<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\InputType;
use Platform\Attributes\Types\TypeInterface;
use Cartalyst\Attributes\EntityInterface;
use Platform\Attributes\Models\Attribute;

class BaseType extends InputType implements TypeInterface
{
	/**
     * Type identifier.
     *
     * @var string
     */
    protected $identifier;

    /**
     * Flag for whether this attribute type can have options.
     *
     * @var bool
     */
    protected $allowOptions = false;

    /**
     * {@inheritDoc}
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return trans("sanatorium/inputs::types.option.{$this->identifier}");
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityFormHtml(Attribute $attribute, EntityInterface $entity)
    {
        return view("sanatorium/inputs::types/{$this->identifier}", compact('attribute', 'entity'));
    }

    /**
     * {@inheritDoc}
     */
    public function allowOptions()
    {
        return $this->allowOptions;
    }

}