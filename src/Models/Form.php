<?php namespace Sanatorium\Inputs\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model {

	/**
	 * {@inheritDoc}
	 */
	protected $table = 'forms';

	/**
	 * {@inheritDoc}
	 */
	protected $guarded = [
		'id',
	];

	/**
	 * {@inheritDoc}
	 */
	protected $with = [
	];

    public function setSettingsAttribute($value)
    {
        if ( is_array($value) )
            $this->attributes['settings'] = json_encode($value);
        else
            $this->attributes['settings'] = $value;
    }

}
