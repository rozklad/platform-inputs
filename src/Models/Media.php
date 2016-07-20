<?php namespace Sanatorium\Inputs\Models;

use Platform\Media\Models\Media as PlatformMedia;
use Sanatorium\Inputs\Traits\ThumbableTrait;

class Media extends PlatformMedia {

    use ThumbableTrait;

	public $morphClass = 'Platform\Media\Models\Media';
	
	public function getUrlAttribute()
	{
		return route('media.view', $this->path);
	}

}