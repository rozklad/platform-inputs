<?php namespace Sanatorium\Inputs\Validator\Attributes;

use Cartalyst\Support\Validator;
use Platform\Attributes\Validator\AttributeValidatorInterface;

class AttributeValidator extends Validator implements AttributeValidatorInterface
{
    /**
     * {@inheritDoc}
     */
    protected $rules = [
        'name'    => 'required',
        'slug'    => 'required|alpha_dash|unique:attributes',
        'type'    => 'required',
        'options' => 'required_if:type,checkbox,radio,select,multiselect',
        'enabled' => 'required',
    ];

    /**
     * {@inheritDoc}
     */
    public function onUpdate()
    {
        $this->rules['slug'] .= ',id,{id}';
    }
}
