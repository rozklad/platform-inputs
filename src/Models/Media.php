<?php namespace Sanatorium\Inputs\Models;

use Platform\Media\Models\Media as PlatformMedia;

class Media extends PlatformMedia {

    protected $appends = [];

	public $morphClass = 'Platform\Media\Models\Media';

}