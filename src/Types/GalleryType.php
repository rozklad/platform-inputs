<?php

namespace Sanatorium\Inputs\Types;

use Platform\Attributes\Types\TypeInterface;

use Cartalyst\Attributes\EntityInterface;
use Platform\Attributes\Models\Attribute;

class GalleryType extends BaseType implements TypeInterface
{

    /**
     * {@inheritDoc}
     */
    protected $identifier = 'gallery';

    protected $allowOptions = true;

    public function allowOptions()
    {
        return $this->allowOptions;
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityFormHtml(Attribute $attribute, EntityInterface $entity)
    {
        $mode = 'multiple';

        return view("sanatorium/inputs::types/media", compact('attribute', 'entity', 'mode'));
    }

}
