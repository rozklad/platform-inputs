<?php

/*
|--------------------------------------------------------------------------
| Media related
|--------------------------------------------------------------------------
|
|
|
*/

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

/*
|--------------------------------------------------------------------------
| Display values related
|--------------------------------------------------------------------------
|
|
*/

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

if ( !function_exists('str_links') )
{
    /**
     * Shorthand for textUrls2Links
     * @todo remove textUrls2Links from codebase and then merge function to this one
     * @param $text
     * @return mixed
     */
    function str_links($text) {
        return textUrls2Links($text);
    }
}

if ( !function_exists('str_scheme') )
{
    /**
     * Add scheme if missing from url
     * @param        $url
     * @param string $scheme
     * @return string
     */
    function str_scheme($url, $scheme = 'http://')
    {
        return parse_url($url, PHP_URL_SCHEME) === null ?
            $scheme . $url : $url;
    }
}

if ( !function_exists('str_country') )
{
    /**
     * Made for country input type
     * @param   $country    string  Abbrevation for country
     */
    function str_country($country)
    {
        // If input is not abbrevation - return full input
        if ( strlen($country) > 2 )
            return $country;

        $countries_file_path = __DIR__ . '/../storage/countries.json';

        if ( !file_exists($countries_file_path) )
            return $country;

        $countries = json_decode( file_get_contents($countries_file_path), true);

        $key = array_search($country, array_column($countries, 'code'));

        if ( $key === false )
            return $country;

        return $countries[$key]['name'];

    }
}

/*
|--------------------------------------------------------------------------
| Theme related
|--------------------------------------------------------------------------
|
| Bunch of functions elated to themes and their usage,
| it helps inputs to use frontend templates for
| displaying values, sharing asset libraries.
|
*/

use Cartalyst\Themes\Laravel\Facades\Theme;

if (! function_exists('theme_set')) {
    /**
     * Set theme to given theme, use this right before view()
     *
     * @param       $theme   string  Slug of theme to be set
     * @example     theme_set('yourtheme');
     *              return view('yourview');
     */
    function theme_set($theme)
    {
        // If function was used to set known theme namespace
        if ( $theme == 'frontend' || $theme == 'admin' )
            return theme_set_area($theme);

        Theme::setActive( $theme );
    }
}

if (! function_exists('theme_set_fallback')) {
    /**
     * Set fallback theme to given themes, use this right before view()
     *
     * @param       $theme   string  Slug of theme to be set
     * @example     theme_set_fallback('default');
     *              return view('yourview');
     */
    function theme_set_fallback($theme)
    {
        // If function was used to set known theme namespace
        if ( $theme == 'frontend' || $theme == 'admin' )
            return theme_set_area($theme);

        Theme::setFallback( $theme );
    }
}

if (! function_exists('theme_set_area')) {
    /**
     * Set theme and fallback to given "area" themes, use this right before view()
     *
     * @param       $area   string  (frontend|admin)
     * @uses        theme_set()
     * @example     theme_set_area('admin');
     *              return view('yourview');
     */
    function theme_set_area($area = 'frontend')
    {
        if ( $active = config("platform-themes.active.{$area}") )
            Theme::setActive( $active );

        if ( $fallback = config("platform-themes.fallback.{$area}") )
            Theme::setFallback( $fallback );
    }
}

if (! function_exists('theme_admin')) {
    /**
     * Set theme to admin, use this right before view()
     *
     * @uses        theme_set_area()
     * @example     theme_admin();
     *              return view('yourview');
     */
    function theme_admin()
    {
        theme_set_area('admin');
    }
}

if (! function_exists('theme_frontend')) {
    /**
     * Set theme to frontend, use this right before view()
     *
     * @uses        theme_set_area()
     * @example     theme_frontend();
     *              return view('yourview');
     */
    function theme_frontend()
    {
        theme_set_area('frontend');
    }
}
