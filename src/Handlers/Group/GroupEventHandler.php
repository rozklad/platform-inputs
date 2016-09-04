<?php namespace Sanatorium\Inputs\Handlers\Group;

use Illuminate\Events\Dispatcher;
use Sanatorium\Inputs\Models\Group;
use Sanatorium\Inputs\Models\Relation;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;
use Platform\Attributes\Models\Attribute;

class GroupEventHandler extends BaseEventHandler implements GroupEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.inputs.group.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.inputs.group.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.inputs.group.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.inputs.group.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.inputs.group.deleted', __CLASS__.'@deleting');
		$dispatcher->listen('sanatorium.inputs.group.deleted', __CLASS__.'@deleted');

        $dispatcher->listen('platform.attribute.updating', __CLASS__.'@attributeUpdating');
        $dispatcher->listen('platform.attribute.creating', __CLASS__.'@attributeCreating');
        $dispatcher->listen('platform.attribute.created', __CLASS__.'@attributeCreated');
        $dispatcher->listen('platform.attribute.deleted', __CLASS__.'@attributeDeleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Group $group)
	{
		$this->flushCache($group);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Group $group, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Group $group)
	{
		$this->flushCache($group);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleting(Group $group)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Group $group)
	{
		$this->flushCache($group);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Inputs\Models\Group  $group
	 * @return void
	 */
	protected function flushCache(Group $group)
	{
		$this->app['cache']->forget('sanatorium.inputs.group.all');

		$this->app['cache']->forget('sanatorium.inputs.group.'.$group->id);
	}

	protected function attributeConnectedPreferences(Attribute $attribute, array $data)
    {
        // Assign attribute to group
        if ( isset($data['group']) )
            $this->assignGroup($attribute, $data['group']);

        // Assign relation to attribute
        if ( isset($data['relation']) )
            $this->assignRelation($attribute, $data['relation']);

        // Assign attribute settings
        if ( isset($data['settings']) )
            $this->assignSettings($attribute, $data['settings']);
    }

    /**
     * @todo  move to it's own event handler (eq. Sanatorium\Inputs\Handlers\AttributeEventHandler)
     * @param Attribute $attribute
     * @param array     $data
     */
	public function attributeUpdating(Attribute $attribute, array $data)
    {
        // Groups, relations, settings
        $this->attributeConnectedPreferences($attribute, $data);
    }

    /**
     * @todo  move to it's own event handler (eq. Sanatorium\Inputs\Handlers\AttributeEventHandler)
     * @param Attribute $attribute
     * @param array     $data
     */
    public function attributeCreating(array $data)
    {
        file_put_contents($this->getTmpFilePath($data['slug']), json_encode($data));
    }

    /**
     * @todo  move to it's own event handler (eq. Sanatorium\Inputs\Handlers\AttributeEventHandler)
     * @param Attribute $attribute
     * @param array     $data
     */
    public function attributeCreated(Attribute $attribute)
    {
        $tmp_file_path = $this->getTmpFilePath($attribute->slug);

        if ( !file_exists($tmp_file_path) )
            return true;

        $data = json_decode(file_get_contents($tmp_file_path), true);

        // Cleanup tmp file
        unlink($tmp_file_path);

        // Groups, relations, settings
        $this->attributeConnectedPreferences($attribute, $data);

    }

    public function attributeDeleted(Attribute $attribute)
    {
        $this->removeFromGroups($attribute);

        $this->removeRelations($attribute);
    }

    protected function getTmpFilePath($slug = null)
    {
        return __DIR__ . '/../../../tmp/' . $slug . '.json';
    }

    protected function assignGroup(Attribute $attribute, $group_ids)
    {
        if ( !is_array($group_ids) ) {
            $group_ids = [$group_ids];
        }

        foreach( $group_ids as $group_id ) {
            $group = Group::find($group_id);

            if ( $group )
            {
                $group->attributes()->sync([$attribute->id], false);
            }
        }

        // Detach from groups not listed and currently saved with attribute
        foreach( Group::whereHas('attributes', function($query) use ($attribute) {
            $query->where('attribute_id', $attribute->id);
        })->whereNotIn('id', $group_ids)->get() as $group) {
            $group->attributes()->detach($attribute->id);
        }
    }

    protected function assignRelation(Attribute $attribute, $relation = null)
    {
        if ( empty($relation) )
            return false;

        $relation = Relation::firstOrCreate([
            'attribute_id' => $attribute->id,
            'relation' => $relation
        ]);

        if ( request()->has('multiple') )
        {
            $relation->multiple = request()->get('multiple');
            $relation->save();
        }
    }

    protected function assignSettings(Attribute $attribute, $settings = [])
    {
        if ( empty($settings) )
            return false;

        $setting = \Sanatorium\Inputs\Models\AttributeSettings::firstOrCreate([
            'attribute_id' => $attribute->id
        ]);

        $setting->update([
            'settings' => json_encode($settings)
        ]);
    }

    protected function removeFromGroups(Attribute $attribute)
    {
        foreach( Group::whereHas('attributes', function($query) use ($attribute) {
            $query->where('attribute_id', $attribute->id);
        })->get() as $group)
        {
            $group->attributes()->detach($attribute->id);
        }
    }

    protected function removeRelations(Attribute $attribute)
    {
        Relation::where('attribute_id', $attribute->id)->delete();
    }

}
