<?php namespace Sanatorium\Inputs\Handlers\Group;

use Illuminate\Events\Dispatcher;
use Sanatorium\Inputs\Models\Group;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

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

}
