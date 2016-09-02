<?php namespace Sanatorium\Inputs\Widgets;

use Sentinel;

class Live {

	public function make($object, $key = null, $placeholder = null, $type = null, $value = null, $description = null, $name = null, $options = null)
	{
		if ( !is_object($object) )
			return null;

		if ( !isset($key) )
			return null;

		if ( !method_exists($object, 'getEntityNamespace') )
			return null;

		if ( !$object->id )
			return null;

		$entity = $object;

		$entity_type = get_class($object);

		$entity_key = $key;

		$entity_namespace = $object->getEntityNamespace();

		$entity_id = $object->id;

		$attribute = $object->newAttributeModel()->whereNamespace($entity_namespace)->whereSlug($key)->first();

		$show_select_placeholder = config('sanatorium-inputs.show_select_placeholder');

		$switch_edit = config('sanatorium-inputs.switch_edit');

		if ( !isset($name) && is_object($attribute) )
			$name = $attribute->name;

		if ( !isset($description) && is_object($attribute) )
			$description = $attribute->description;

		if ( !isset($placeholder) && is_object($attribute) )
			$placeholder = $attribute->description;

		if ( !isset($type) && is_object($attribute) )
			$type = $attribute->type;

		if ( !isset($type) && !is_object($attribute) )
			$type = 'input';

		if ( !isset($placeholder) && !is_object($attribute) && $key == 'first_name' )
			$placeholder = 'First name';

		if ( !isset($placeholder) && !is_object($attribute) && $key == 'last_name' )
			$placeholder = 'Last name';

		if ( is_null($value) )
			$value = $object->{$key};

		$element_id = 'input-'. $entity_key;

		$label = $name;

		if ( !isset($label) ) 
			$label = ucfirst($type);

		$user_can_manage = false;

		$user_can_edit = self::canEdit($object);

		$user = Sentinel::getUser();

		if ( Sentinel::check() )
			$user_can_manage = Sentinel::hasAnyAccess(['superuser', 'attributes.edit']);

		$score = 0;

		if ( is_object($attribute) )
			$score = $attribute->score;

		// Additional validation and "when empty" settings
		$validation = null;
		$empty = null;

		// @todo: make this dynamic (default values when empty)
		switch( $entity_key ) {

			case 'company_facebook_url':
			case 'facebook_url':
				$validation = 'facebookurl';
				$empty = 'https://www.facebook.com/';
			break;

			case 'company_twitter_url':
			case 'twitter_url':
				$validation = 'twitterurl';
				$empty = 'https://twitter.com';
			break;

			case 'company_linkedin_url':
			case 'linkedin_url':
				$validation = 'linkedinurl';
				$empty = 'https://www.linkedin.com/profile/';
			break;

			case 'website':
				$validation = 'url';
			break;

			case 'general_email':
			case 'work_email':
			case 'private_email':
				$validation = 'email';
			break;

		}
		
		return view('sanatorium/inputs::live', compact(
			'name',
			'label',
			'description',
			'element_id',
			'entity_type', 
			'entity_key',
			'entity_namespace', 
			'entity_id', 
			'entity',
			'placeholder', 
			'object', 
			'attribute', 
			'type', 
			'value',
			'options',
			'show_select_placeholder',
			'switch_edit',
			'user_can_manage',
			'user_can_edit',
			'score',
			'validation',
			'empty')
		);
	}

	public function custom($object, $type = null)
	{
		$options = [];

		switch ( $type ) {

			case 'fullname':

				$value = sprintf('%s %s', $object->first_name, $object->last_name);

			break;

			case 'tags':

				$tags = $object->tags()->where('public', 1)->get()->toArray();

				// $tags = $object->tags->toArray();

				$value = array_column($tags, 'name');

				$options = $object->allTags()->lists('name', 'id')->toArray();

			break;

		}

		return $this->make($object, $type, null, $type, $value, null, null, $options);
	}

	public static function canView($object)
	{
		$currentUser = Sentinel::getUser();

		if ( !Sentinel::check() ) {
			return false;
		}
		if ( Sentinel::hasAnyAccess(['superuser', 'attributes.edit']) )
			return true;

		if ( $currentUser->type == 'individual' ) {
			$currentIndividualUser = \Sleighdogs\Profile\Models\User::find($currentUser->id);
			if ( $currentIndividualUser->isConnectedToCorporate($object->id) ) {
				return true;
			}
		}

		return false;
	}

	public static function canEdit($object)
	{
		$currentUser = Sentinel::getUser();

		if ( !Sentinel::check() ) {
			return false;
		}
		if ( Sentinel::hasAnyAccess(['superuser', 'attributes.edit']) )
			return true;

		if ( $currentUser->type == 'individual' ) {
			$currentIndividualUser = \Sleighdogs\Profile\Models\User::find($currentUser->id);
			if ( $currentIndividualUser->isCapableToCorporate($object->id) ) {
				return true;
			}
		}

		if ( is_object($currentUser) ) {
			if ( $currentUser->id == $object->id ) {
				return true;
			}
		}

		return false;
	}

}