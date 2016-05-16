<?php

namespace Sanatorium\Inputs\Types;

use Cartalyst\Attributes\EntityInterface;
use Platform\Attributes\Models\Attribute;

class VideoType extends MediaType
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'video';

    /**
     * {@inheritDoc}
     */
    public function getEntityFormHtml(Attribute $attribute, EntityInterface $entity)
    {
        $mode = 'single';
        $filter = 'video';
        $types = ['video/mp4', 'video/ogg'];
        return view("sanatorium/inputs::types/media", compact('attribute', 'entity', 'mode', 'filter', 'types'));
    }
}
