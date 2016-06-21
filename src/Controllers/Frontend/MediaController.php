<?php namespace Sanatorium\Inputs\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Platform\Media\Repositories\MediaRepositoryInterface;
use Filesystem;
use Cartalyst\Filesystem\File;
use Cache;
use Platform\Media\Models\Media;
use StorageUrl;

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
     * Static approach
     * @return mixed
     */
    public function getMediaAll($orderBy = 'created_at', $orderWay = 'desc')
    {
        $media = app('platform.media');

        return $media->orderBy($orderBy, $orderWay)->get();
    }

    /**
     * Returns all media available in the system
     */
    public function getMedia($orderBy = 'created_at', $orderWay = 'desc')
    {
        return ( $this->fetch
            ? $this->fetch( $this->media->orderBy($orderBy, $orderWay)->get() )
            : $this->media->orderBy($orderBy, $orderWay)->get()
        );
    }

    public function fetch($media)
    {
        switch ( get_class($media) ) {

            case 'Illuminate\Database\Eloquent\Collection':
                return $this->fetchAll($media);
                break;

            case 'Platform\Media\Models\Media':

                $media->public_url = StorageUrl::url($media->path);
                $media->thumbnail_uri = StorageUrl::url( \Sanatorium\Shop\Models\Product::thumbnailPath($media, 300) ); // @todo: move the logic from product
                $media->thumbnail = StorageUrl::url( \Sanatorium\Shop\Models\Product::thumbnailPath($media, 300) );
                $media->view_uri = route('media.view', $media->path);
                $media->edit_uri = route('admin.media.edit', $media->id);
                $media->delete_uri = route('sanatorium.inputs.media.delete', $media->id);
                $media->email_uri = route('admin.media.email', $media->id);
                $media->download_uri = route('media.download', $media->path);
                $media->view_uri = route('media.view', $media->path);
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


    /**
     * Removes the specified media.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse or array if is ajax
     */
    public function delete($id)
    {
        $type = $this->media->delete($id) ? 'success' : 'error';

        $this->alerts->{$type}(
            trans("platform/media::message.{$type}.delete")
        );

        if ( request()->ajax() )
        {

            return [
                'type' => $type
            ];

        }

        return redirect()->route('admin.media.all');
    }

    public function upload()
    {
        $medium = null;

        if( request()->hasFile('file') )
        {

            $medium = app('platform.media')->upload(request()->file('file'), []);

        }

        return $medium;
    }

}
