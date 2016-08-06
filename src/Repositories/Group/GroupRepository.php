<?php namespace Sanatorium\Inputs\Repositories\Group;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class GroupRepository implements GroupRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Inputs\Handlers\Group\GroupDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent inputs model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.inputs.group.handler.data'];

		$this->setValidator($app['sanatorium.inputs.group.validator']);

		$this->setModel(get_class($app['Sanatorium\Inputs\Models\Group']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.inputs.group.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.inputs.group.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new group
		$group = $this->createModel();

		// Fire the 'sanatorium.inputs.group.creating' event
		if ($this->fireEvent('sanatorium.inputs.group.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the group
			$group->fill($data)->save();

			// Fire the 'sanatorium.inputs.group.created' event
			$this->fireEvent('sanatorium.inputs.group.created', [ $group ]);
		}

		return [ $messages, $group ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the group object
		$group = $this->find($id);

		// Fire the 'sanatorium.inputs.group.updating' event
		if ($this->fireEvent('sanatorium.inputs.group.updating', [ $group, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($group, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the group
			$group->fill($data)->save();

			// Fire the 'sanatorium.inputs.group.updated' event
			$this->fireEvent('sanatorium.inputs.group.updated', [ $group ]);
		}

		return [ $messages, $group ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the group exists
		if ($group = $this->find($id))
		{
			// Fire the 'sanatorium.inputs.group.deleting' event
			$this->fireEvent('sanatorium.inputs.group.deleting', [ $group ]);

			// Delete the group entry
			$group->delete();

			// Fire the 'sanatorium.inputs.group.deleted' event
			$this->fireEvent('sanatorium.inputs.group.deleted', [ $group ]);

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
