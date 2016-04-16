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

use Platform\Attributes\Types\TypeInterface;

class MediaType extends BaseType implements TypeInterface
{
    /**
     * {@inheritDoc}
     */
    protected $identifier = 'media';

    /**
     * Returns all media available in the system
     */
    public static function getMedia()
    {
        $media = app('platform.media');

        return $media->all();
    }

    /**
     * Return media assigned to the current
     *
     * @example MediaType::getMediaAssignedToEntity(3, 'Platform\Pages\Models\Page')
     * @param $object_id Assigned Object ID
     * @param $object_type Assigned Class of object
     */
    public static function getMediaAssignedToEntity(Integer $object_id, String $object_type)
    {
        $media = app('platform.media');

        // @todo add where condition(s)
        return $media->get();
    }

}
