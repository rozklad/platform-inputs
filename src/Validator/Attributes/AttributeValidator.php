<?php namespace Sanatorium\Inputs\Validator\Attributes;

use Platform\Attributes\Validator\AttributeValidatorInterface;
use Cartalyst\Support\Validator;

class AttributeValidator extends Validator implements AttributeValidatorInterface
{
    /**
     * {@inheritDoc}
     */
    protected $rules = [
        'name'    => 'required',
        'slug'    => 'required|alpha_dash|unique:attributes',
        'type'    => 'required',
        'options' => 'required_if:type,checkbox,radio,select',
        'enabled' => 'required',
    ];

    /**
     * {@inheritDoc}
     */
    public function onUpdate()
    {
        $this->rules['slug'] .= ',slug,{slug},slug';
    }
}
