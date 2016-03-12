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

	public function addRemoteMedia($file_url = null, $cover = false)
	{
		if ( empty($file_url) ) return false;

		// Obtain data from source
		$data = @file_get_contents($file_url); # File::get() validates, so its better to use file_get_contents()
		
		// Check if data was obtained
		if ( empty($data) ) return false;

		/* File name */
		$file_name = basename($file_url);
		/* If fileName contains '?' (followed with GET data) trim the part starting with '?' */
		$index = strpos($file_name, '?');
		if ($index !== FALSE) {
			$file_name = substr($file_name, 0, $index);
		}

		$temporary_path = storage_path('/media/tmp/') . $file_name;

		/* Folder does not exist */
		if (!file_exists(dirname($temporary_path))) {
			mkdir( dirname($temporary_path), 0777, true );
		}

		$add_extension = null;

		/* Make extension if source file does not have one */
		if (!File::extension($temporary_path)) {
			
			$finfo = new \finfo(FILEINFO_MIME);
			$parts = explode( ';', $finfo->buffer($data) ); # buffer returns f.e. 'image/jpeg; charset=binary'

			switch( $parts[0] ) {
				case 'image/jpeg':
					$add_extension = $temporary_path . '.jpg';
				break;
			}
		};

		// Save to local temporary file
		$saved = File::put($temporary_path, $data);

		// Upload the file to media folder
		$file = new UploadedFile($temporary_path, basename($file_url) . $add_extension);

		// Use Media facade to upload the file
		$uploaded = Filesystem::upload($file);

		// Get image size by Symfony UploadedFile method
		$imageSize = $uploaded->getImageSize();

		// Assign tags
		$tags = ['imported'];

		// Cover
		if ( $cover ) {
			$tags[] = 'cover';
		}

		// Create media model instance
		$media = new MediaModel([
				'name'      => $file->getClientOriginalName(),
				'path'      => $uploaded->getPath(),
				'extension' => $uploaded->getExtension(),
				'mime'      => $uploaded->getMimetype(),
				'size'      => $uploaded->getSize(),
				'is_image'  => $uploaded->isImage(),
				'width'     => $imageSize['width'],
				'height'    => $imageSize['height'],
				'tags'      => $tags,
				]);

		// Cleanup temporary file
		File::delete($temporary_path);

		// Assign media to this model
		return $this->media()->save($media);

	}

}