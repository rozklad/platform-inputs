<?php namespace Sanatorium\Inputs\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Platform\Media\Repositories\MediaRepositoryInterface;

class MediaController extends Controller {

    public function __construct(MediaRepositoryInterface $media)
    {

        $this->media = $media;

        parent::__construct();

    }

    /**
     * Static version of getMedia
     * @return mixed
     */
    public static function getMediaAll()
    {
        $media = app('platform.media');

        return $media->all();
    }

    /**
     * Returns all media available in the system
     */
    public function getMedia()
    {
        return $this->media->all();
    }

    /**
     * Return media assigned to the current
     *
     * @example MediaType::getMediaAssignedToEntity(3, 'Platform\Pages\Models\Page')
     * @param $entity_id Assigned Object ID
     * @param $entity_type Assigned Class of entity
     */
    public function getMediaAssignedToEntity($entity_id, $entity_type)
    {
        $media = app('platform.media');

        return $this->media
            ->leftJoin('media_assign', function($join){
                $join->on('media.id', '=', 'media_assign.entity_id');
            })
            ->where('media_assign.entity_type', $entity_type)
            ->where('media_assign.entity_id', $entity_id)
            ->get();
    }

}
