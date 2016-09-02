<?php namespace Sanatorium\Inputs\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Sentinel;

/**
 * @todo: Decouple from Profiles
 */

class LiveController extends Controller {

	public function edit()
	{
		extract(request()->all());

		// Value does not exist
		if ( !isset($value) )
			return ['error' => true, 'reason' => 'Value was not set'];

		$object = $editable_type::find($editable_id);

		// Object does not exist
		if ( !$object )
			return ['error' => true, 'reason' => 'Object does not exist'];

		// Curent user does not have sufficient rights to edit
		if ( !self::hasAccess($editable_type, $editable_id) )
			return ['error' => true, 'reason' => 'Insufficient rights'];

		switch( $editable_input ) {

			case 'tags':
				$object->setTags($value, 'slug');		
			break;

			case 'checkbox':
				if ( is_array($value) ) {
					$object->{$editable_key} = $value;
				} elseif ( $value !== 'false' ) {
					$object->{$editable_key} = 1;
				} else {
					$object->{$editable_key} = 0;
				}
			break;

			case 'repeater':
				$object->{$editable_key} = $value;
			break;

			default:
				$object->{$editable_key} = $value;
			break;

		}

		$result = $object->save();

		$response = [
			'error' => !$result,
			'object' => $object,
		];

		if ( config('sanatorium-inputs.send_profile_percentage') ) {
			$response['percentage'] = \Sleighdogs\Profile\Widgets\Stats::getProfilePercentage($object);
		}

		return $response;
	}

	public static function hasAccess($editable_type, $editable_id)
	{

		$current_user = Sentinel::getUser();

		switch ( $editable_type ) {

			// Check if the current logged in user has access to modify the edited user
			case 'Platform\Users\Models\User':
			case 'Sleighdogs\Profile\Models\User':
			case 'Sleighdogs\Profile\Models\Corporate':

				$object = $editable_type::find($editable_id);

				// User is editing himself
				if ( \Sanatorium\Inputs\Widgets\Live::canEdit($object) )
					return true;

				return false;

			break;

			default:

				return false;

			break;

		}

	}
}
