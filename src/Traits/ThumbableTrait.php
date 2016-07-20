<?php namespace Sanatorium\Inputs\Traits;

use Illuminate\Support\Str;

trait ThumbableTrait
{

    /**
     * @todo: derive base path from style
     * @param     $media
     * @param int $width
     * @param int $height
     * @return mixed|string
     */
    public static function thumbnailPath($media, $width = 300, $height = 300)
    {
        $name = '';

        if ( $width != 'full' || $height != 'full' )
            $name = Str::slug(implode('-', [$width, $height ?: $width]));

        $extension = \Sanatorium\Thumbs\Styles\Macros\ThumbsMacro::getExtension($media);

        return "cache/thumbs/{$media->id}_{$name}.{$extension}";
    }

}