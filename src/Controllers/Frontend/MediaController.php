<?php namespace Sanatorium\Inputs\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Platform\Media\Repositories\MediaRepositoryInterface;
use Filesystem;
use Cartalyst\Filesystem\File;
use Cache;
use Platform\Media\Models\Media;

class MediaController extends Controller {

    /**
     * Fetch 3rd party file information (s3, dropbox etc.)
     * @var bool
     */
    protected $fetch = true;

    public function __construct(MediaRepositoryInterface $media)
    {

        $this->media = $media;

        parent::__construct();

    }

    /**
     * Static version of getMedia
     * @return mixed
     */
    public function getMediaAll()
    {
        $media = app('platform.media');

        return $media->all();
    }

    /**
     * Returns all media available in the system
     */
    public function getMedia()
    {
        return ( $this->fetch ? $this->fetch( $this->media->all() ) : $this->media->all() );
    }

    public function fetch($media)
    {
        switch ( get_class($media) ) {

            case 'Illuminate\Database\Eloquent\Collection':
                return $this->fetchAll($media);
                break;

            case 'Platform\Media\Models\Media':

                $media->public_url = self::getPublicUrl($media, '+2 days');
                $media->thumbnail_uri = url($media->thumbnail);
                $media->view_uri = route('media.view', $media->path);
                $media->edit_uri = route('admin.media.edit', $media->id);
                $media->delete_uri = route('admin.media.delete', $media->id);
                $media->email_uri = route('admin.media.email', $media->id);
                $media->download_uri = route('media.download', $media->path);
                $media->tags = $media->tags;

                return $media;
                break;
        }
    }

    public function fetchAll($media)
    {
        $results = [];

        foreach ( $media as $medium )
        {
            $results[] = $this->fetch($medium);
        }

        return $results;
    }

    /**
     * Return public URL to media id
     *
     * @param      $media_id
     * @param null $expires
     * @return mixed
     */
    public static function getPublicUrlById($media_id, $expires = null)
    {
        $media = app('platform.media');

        return self::getPublicUrl($media->find($media_id));
    }

    /**
     * Return public URL to media object
     *
     * @param Media $media
     * @param null  $expires The time at which the URL should expire
     * @return mixed
     */
    public static function getPublicUrl(Media $media, $expires = null)
    {
        $expires_in_minutes = (strtotime($expires) - time()) / 60;

        return Cache::remember('media.public.url.'.$media->id, $expires_in_minutes, function() use ($media, $expires) {
            $file = Filesystem::get($media->path);
            return self::getPublicFileUrl($file, $expires);
        });
    }

    /**
     * Return public URL to file
     *
     * @param      $file
     * @param mixed  $expires The time at which the URL should expire
     */
    public static function getPublicFileUrl(File $file, $expires = null)
    {
        switch( config('cartalyst.filesystem.default') ) {

            case 'awss3':
                    return $file->getAdapter()->getClient()->getObjectUrl(
                        config('cartalyst.filesystem.connections.awss3.bucket'),
                        config('cartalyst.filesystem.connections.awss3.prefix') . '/' . $file->getPath(),
                        $expires);
                break;

            // @todo: add other file storages

        }

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
        return $this->media
            ->leftJoin('media_assign', function($join){
                $join->on('media.id', '=', 'media_assign.entity_id');
            })
            ->where('media_assign.entity_type', $entity_type)
            ->where('media_assign.entity_id', $entity_id)
            ->get();
    }

}
