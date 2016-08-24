<?php

use Illuminate\Support\Str;

if ( !function_exists('storage_url') )
{
    /**
     * Return url to given media
     *
     * @param $media mixed Object media, id of media, path to media
     * @return null
     */
    function storage_url($media) {

        if ( is_numeric($media) )
        {
            $media = app('platform.media')->find($media);
        }

        if ( !is_object($media) )
            $path = $media;
        else
            $path = $media->path;

        return StorageUrl::url($path);
    }
}

if ( !function_exists('thumbnail_url') )
{
    /**
     * Returns url to thumbnail of given dimensions
     *
     * @param     $media
     * @param int $width
     * @param int $height
     * @return null
     */
    function thumbnail_url($media, $width = 300, $height = 300)
    {
        $name = '';

        if ( $width != 'full' || $height != 'full' )
            $name = Str::slug(implode('-', [$width, $height ?: $width]));

        if ( is_numeric($media) )
        {
            $media = app('platform.media')->find($media);
        } else if ( is_string($media) )
        {
            $media = app('platform.media')->where('path', $media)->first();
        }

        if ( !is_object($media) )
            return null;

        $extension = mime2Extension($media->mime);

        return storage_url( "cache/thumbs/{$media->id}_{$name}.{$extension}" );
    }
}

if ( !function_exists('mime2Extension') )
{
    /**
     * Returns extension to given mime type
     *
     * @param        $mime      Mime type (f.e image/png)
     * @param string $extension Fallback value
     * @return string
     */
    function mime2Extension($mime, $extension = '') {

        // If there is no extension, let's give it one
        switch ($mime) {
            case 'image/jpeg':
                $extension = 'jpg';
                break;

            case 'image/png':
                $extension = 'png';
                break;

            case 'image/gif':
                $extension = 'gif';
                break;

            case 'image/bmp':
                $extension = 'bmp';
                break;

        }

        return $extension;
    }
}

if ( !function_exists('textUrls2Links') )
{
    function textUrls2Links($text) {

        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        preg_match_all($reg_exUrl, $text, $matches);
        $usedPatterns = array();
        foreach($matches[0] as $pattern){
            if(!array_key_exists($pattern, $usedPatterns)) {
                $usedPatterns[$pattern]=true;
                $text = str_replace($pattern, "<a href=\"{$pattern}\" rel=\"nofollow\">{$pattern}</a> ", $text);
            }
        }
        return $text;

    }
}