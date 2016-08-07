<?php

/**
 * Class MediaType
 *
 * Part of the Sanatorium\Inputs\Types extension.
 *
 * NOTICE OF LICENSE
 *
 * @package    Sanatorium\Inputs\Types
 * @version    1.0.0
 * @author     Sanatorium
 * @license    WTFPL
 * @copyright  (c) 2015-2016, Sanatorium
 * @link       http://sanatorium.ninja
 */

namespace Sanatorium\Inputs\Types;

use Cartalyst\Attributes\EntityInterface;
use Platform\Attributes\Models\Attribute;

class MediaType extends BaseType
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'media';

    /**
     * {@inheritDoc}
     */
    public function getEntityFormHtml(Attribute $attribute, EntityInterface $entity)
    {
        $mode = 'multiple';
        $filter = 'images';
        $types = ['image/png', 'image/jpg', 'image/gif'];
        $label = trans('sanatorium/inputs::types.media.select');
        return view("sanatorium/inputs::types/media", compact('attribute', 'entity', 'mode', 'filter', 'types', 'label'));
    }

}
