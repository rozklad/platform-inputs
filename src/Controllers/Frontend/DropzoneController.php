<?php namespace Sanatorium\Inputs\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Platform\Media\Repositories\MediaRepositoryInterface;
use Illuminate\Http\Request;
use DB;

class DropzoneController extends Controller {

	public function upload(Request $request, MediaRepositoryInterface $media)
	{
		$files = request()->file('file');
		$entity_type = str_replace('(BACKSLASH)', '\\', request()->get('entity_type'));
		$entity_id = request()->get('entity_id');
		$attribute_slug = request()->get('attribute_slug');

		foreach( $files as $file ) 
		{
			$fileobject = $media->upload($file, ['tags' => [$attribute_slug] ]);
			
			DB::insert('insert into media_assign (entity_id, entity_type, media_id) values (?, ?, ?)', [$entity_id, $entity_type, $fileobject->id]);
		}

		if ($request->ajax()) {
			return ['success' => true];
		}

		return redirect()->back();
	}

	public function avatar(Request $request, MediaRepositoryInterface $media, $single = true)
	{
		$file = request()->file('file');
		$entity_type = str_replace('(BACKSLASH)', '\\', request()->get('entity_type'));
		$entity_id = request()->get('entity_id');
		$attribute_slug = request()->get('attribute_slug');
			
		// If media should be only single image
		if ( $single ) {
			$object = $entity_type::find($entity_id);
			
			foreach($object->mediaByTag($attribute_slug) as $medium) {
				$medium->untag($attribute_slug);
			}

		}

		$fileobject = $media->upload($file, ['tags' => [$attribute_slug] ]);

		DB::insert('insert into media_assign (entity_id, entity_type, media_id) values (?, ?, ?)', [$entity_id, $entity_type, $fileobject->id]);

		if ($request->ajax()) {
			return $fileobject;
		}

		return redirect()->back();
	}


	public function single(Request $request, MediaRepositoryInterface $media)
	{
		$file = request()->file('file');

		$fileobject = $media->upload($file, []);

		if ($request->ajax()) {
			$fileobject->image = route('media.view', ['path' => $fileobject->path]);
			return $fileobject;
		}

		return redirect()->back();
	}

	public function delete(Request $request, MediaRepositoryInterface $media)
	{
		$media_id = request()->get('media_id');
		\Platform\Media\Models\Media::destroy($media_id);

		return array('status' => true);
	}

	public function cover()
	{
		$media_id = request()->get('id');
		$product_id = request()->get('product_id');

		if ( !$product_id || !$media_id )
			return;

		$product = \Product::find( $product_id );

		if ( !$product )
			return;

		if ( !method_exists($product, 'setCoverImage') )
			return;

		$product->setCoverImage($media_id);

		return ['success' => true];
	}

	public function options($only_images = true)
	{

		$this->media = app('Platform\Media\Repositories\MediaRepositoryInterface');

		$media = $this->media;

		if ( $only_images )
			$media = $media->where('is_image', 1);

		$media = $media->get();

		$results = [];

		foreach( $media as $medium ) {
			$results[] = [
				'id' => $medium->id,
				'image' => route('media.view', ['path' => $medium->path]),
				'name' => $medium->name
			];
		}

		return $results;
	}
}
