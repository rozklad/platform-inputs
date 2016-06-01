<?php namespace Sanatorium\Inputs\Traits;

use Platform\Media\Repositories\MediaRepositoryInterface;
use File;
use Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Platform\Media\Models\Media as MediaModel;

trait MediableTrait {

	public function media()
	{
		return $this->morphToMany('Sanatorium\Inputs\Models\Media', 'entity', 'media_assign');
	}

	public static function normalizeTag($tag)
	{
		return str_replace('_', '-', $tag);
	}

	public function mediaByTag($tag = null)
	{
		return $this->media()->withTag(self::normalizeTag($tag))->get();
	}

	public function scopeMediaTag()
	{
		return $this->media()->withTag(self::normalizeTag($tag));
	}

	/**
	 * @todo delete this function and fallback cover image functions
	 * @return [type] [description]
	 */
	public function getHasRealCoverImageAttribute()
	{
		// Primary
		if ( is_object($this->media()->withTag('cover')->first()) )
			return true;

		return false;
	}

	public function getHasCoverImageAttribute()
	{
		// Primary
		if ( is_object($this->media()->withTag('cover')->first()) )
			return true;

		// Fallback
		if ( is_object($this->media()->first()) )
			return true;

		return false;
	}

	public function getCoverImageAttribute()
	{
		// Primary
		if ( $cover = $this->media()->withTag('cover')->first() )
			return $cover;

		// Fallback
		$cover = $this->media()->first();

		if ( is_object($cover) )
			return $cover;

		return null;
	}

	public function setCoverImage($media_id = null)
	{
		if ( !$media_id )
			return false;

		foreach( $this->media()->get() as $media ) 
		{
			$media->untag('cover');
		}

		$cover = $this->media()->find($media_id);

		if ( !$cover )
			return ['success' => false];

		$cover->tag('cover');

		return ['success' => true];		
	}

	public function getCoverImageUrlAttribute()
	{
		$cover = $this->cover_image;

		if ( is_object($cover) )
			return route('media.view', $cover->path);

		return null;
	}
	
}