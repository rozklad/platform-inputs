<?php namespace Sanatorium\Inputs\Widgets;

class Media {

	public function select($object, $single = true, $only_images = true)
	{
		$this->media = app('Platform\Media\Repositories\MediaRepositoryInterface');

		$media = $this->media;

		if ( $only_images )
			$media = $media->where('is_image', 1);

		$media = $media->get();

		return view('sanatorium/inputs::widgets/media_select', compact('single', 'object', 'media'));
	}

}