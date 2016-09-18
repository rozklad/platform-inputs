<?php namespace Sanatorium\Inputs\Widgets;

class Display {

    public function __construct()
    {
        $this->media = app('Platform\Media\Repositories\MediaRepositoryInterface');

        $this->attributes = app('platform.attributes');
    }

    /**
     * @param        $entity    Entity to get data from
     * @param        $slug      Attribute to show by slug
     * @param string $mode      Display mode
     * @param string $action    Possible values (view|edit)
     * @return null
     */
    public function show($entity, $slug, $mode = 'single', $action = 'view')
    {
        if ( $mode == null )
            $mode = 'multiple';

        if ( !$attribute = $this->attributes->whereSlug($slug)->whereNamespace($entity->getEntityNamespace())->first() )
            return null;

        $media = [];

        $type = $attribute->type;

        $template = $type;

        switch($type) {

            case 'media':
            case 'gallery':
            case 'image':
            case 'file':
                $media = $this->getMedia($entity, $slug);
                $template = 'media';
                break;

        }

        return view("sanatorium/inputs::display/{$template}", compact('entity', 'slug', 'attribute', 'media', 'mode', 'type', 'action'));
    }


    public function getMedia($entity, $slug)
    {
        $value = $entity->{$slug};
        if ( !$value )
            return [];

        if ( is_numeric($value) )
            $value = [$value];

        if ( !is_array($value) )
            return [];

        return $this->media->whereIn('id', $value)->get();

    }

}