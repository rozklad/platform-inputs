<?php namespace Sanatorium\Inputs\Widgets;

class Display {

    public function __construct()
    {
        $this->media = app('Platform\Media\Repositories\MediaRepositoryInterface');

        $this->attributes = app('platform.attributes');
    }

    public function show($entity, $slug, $mode = 'single')
    {

        if ( !$attribute = $this->attributes->whereSlug($slug)->whereNamespace($entity->getEntityNamespace())->first() )
            return null;
        
        $media = $this->getMedia($entity, $slug, $mode);
        
        return view('sanatorium/inputs::widgets/display', compact('entity', 'slug', 'attribute', 'media', 'mode'));
    }


    public function getMedia($entity, $slug, $mode)
    {
        if ( !$entity->{$slug} )
            return null;

        switch( $mode ) {

            case 'single':
                    return [$this->media->find($entity->{$slug})];
                break;

            case 'multiple':
                    $return = $this->media->whereIn('id', array_values($entity->{$slug}))->get();
                    return $return;
                break;

        }

    }

}