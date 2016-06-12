<?php

namespace Sanatorium\Inputs\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Platform\Tags\Repositories\TagsRepositoryInterface;
use Platform\Roles\Repositories\RoleRepositoryInterface;
use Platform\Media\Repositories\MediaRepositoryInterface;
use League\Flysystem\FileNotFoundException;

class MediaController extends AdminController
{

    /**
     * The Media repository.
     *
     * @var \Platform\Media\Repositories\MediaRepositoryInterface
     */
    protected $media;

    /**
     * The Users Roles repository.
     *
     * @var \Platform\Roles\Repositories\RoleRepositoryInterface
     */
    protected $roles;

    /**
     * The Tags repository.
     *
     * @var \Platform\Tags\Repositories\TagsRepositoryInterface
     */
    protected $tags;

    /**
     * Holds all the mass actions we can execute.
     *
     * @var array
     */
    protected $actions = [
        'delete',
        'makePrivate',
        'makePublic',
    ];

    /**
     * Constructor.
     *
     * @param  \Platform\Media\Repositories\MediaRepositoryInterface $media
     * @param  \Platform\Roles\Repositories\RoleRepositoryInterface  $roles
     * @param  \Platform\Tags\Repositories\TagsRepositoryInterface   $tags
     * @return void
     */
    public function __construct(
        MediaRepositoryInterface $media,
        RoleRepositoryInterface $roles,
        TagsRepositoryInterface $tags
    )
    {
        parent::__construct();

        $this->media = $media;

        $this->roles = $roles;

        $this->tags = $tags;
    }

    /**
     * Removes the specified media.
     *
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse or array if is ajax
     */
    public function delete($id)
    {
        try
        {
            $type = $this->media->delete($id) ? 'success' : 'error';
        } catch (FileNotFoundException $e)
        {
            // The file as not physically found, but we still may delete the DB entry
            $type = \Platform\Media\Models\Media::find($id)->delete() ? 'success' : 'error';
        }

        $this->alerts->{$type}(
            trans("platform/media::message.{$type}.delete")
        );

        if ( request()->ajax() )
        {

            return [
                'type' => $type,
                'id'   => $id,
            ];

        }

        return redirect()->route('admin.media.all');
    }

}
