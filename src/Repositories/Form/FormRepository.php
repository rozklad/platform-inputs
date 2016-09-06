<?php namespace Sanatorium\Inputs\Repositories\Form;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class FormRepository implements FormRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Inputs\Handlers\Form\FormDataHandlerInterface
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

		$this->data = $app['sanatorium.inputs.form.handler.data'];

		$this->setValidator($app['sanatorium.inputs.form.validator']);

		$this->setModel(get_class($app['Sanatorium\Inputs\Models\Form']));
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
		return $this->container['cache']->rememberForever('sanatorium.inputs.form.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.inputs.form.'.$id, function() use ($id)
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
		// Create a new form
		$form = $this->createModel();

		// Fire the 'sanatorium.inputs.form.creating' event
		if ($this->fireEvent('sanatorium.inputs.form.creating', [ $input ]) === false)
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
			// Save the form
			$form->fill($data)->save();

			// Fire the 'sanatorium.inputs.form.created' event
			$this->fireEvent('sanatorium.inputs.form.created', [ $form ]);
		}

		return [ $messages, $form ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the form object
		$form = $this->find($id);

		// Fire the 'sanatorium.inputs.form.updating' event
		if ($this->fireEvent('sanatorium.inputs.form.updating', [ $form, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($form, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the form
			$form->fill($data)->save();

			// Fire the 'sanatorium.inputs.form.updated' event
			$this->fireEvent('sanatorium.inputs.form.updated', [ $form ]);
		}

		return [ $messages, $form ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the form exists
		if ($form = $this->find($id))
		{
			// Fire the 'sanatorium.inputs.form.deleting' event
			$this->fireEvent('sanatorium.inputs.form.deleting', [ $form ]);

			// Delete the form entry
			$form->delete();

			// Fire the 'sanatorium.inputs.form.deleted' event
			$this->fireEvent('sanatorium.inputs.form.deleted', [ $form ]);

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
