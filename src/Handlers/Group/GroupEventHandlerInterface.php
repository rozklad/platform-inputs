<?php namespace Sanatorium\Inputs\Handlers\Group;

use Sanatorium\Inputs\Models\Group;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface GroupEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a group is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a group is created.
	 *
	 * @param  \Sanatorium\Inputs\Models\Group  $group
	 * @return mixed
	 */
	public function created(Group $group);

	/**
	 * When a group is being updated.
	 *
	 * @param  \Sanatorium\Inputs\Models\Group  $group
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Group $group, array $data);

	/**
	 * When a group is updated.
	 *
	 * @param  \Sanatorium\Inputs\Models\Group  $group
	 * @return mixed
	 */
	public function updated(Group $group);

	/**
	 * When a group is being deleted.
	 *
	 * @param  \Sanatorium\Inputs\Models\Group  $group
	 * @return mixed
	 */
	public function deleting(Group $group);

	/**
	 * When a group is deleted.
	 *
	 * @param  \Sanatorium\Inputs\Models\Group  $group
	 * @return mixed
	 */
	public function deleted(Group $group);

}
