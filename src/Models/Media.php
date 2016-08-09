<?php namespace Sanatorium\Inputs\Models;

use Platform\Media\Models\Media as PlatformMedia;

class Media extends PlatformMedia {

	public $morphClass = 'Platform\Media\Models\Media';
	
	public function getUrlAttribute()
	{
		return route('media.view', $this->path);
	}

}