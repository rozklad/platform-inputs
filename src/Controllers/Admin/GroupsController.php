<?php namespace Sanatorium\Inputs\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Inputs\Repositories\Group\GroupRepositoryInterface;

class GroupsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Inputs repository.
	 *
	 * @var \Sanatorium\Inputs\Repositories\Group\GroupRepositoryInterface
	 */
	protected $groups;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Inputs\Repositories\Group\GroupRepositoryInterface  $groups
	 * @return void
	 */
	public function __construct(GroupRepositoryInterface $groups)
	{
		parent::__construct();

		$this->groups = $groups;
	}

	/**
	 * Display a listing of group.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/inputs::groups.index');
	}

	/**
	 * Datasource for the group Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->groups->grid();

		$columns = [
			'id',
			'name',
			'slug',
			'weight',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.inputs.groups.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new group.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new group.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating group.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating group.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified group.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->groups->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/inputs::groups/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.inputs.groups.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->groups->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a group identifier?
		if (isset($id))
		{
			if ( ! $group = $this->groups->find($id))
			{
				$this->alerts->error(trans('sanatorium/inputs::groups/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.inputs.groups.all');
			}
		}
		else
		{
			$group = $this->groups->createModel();
		}

		// Show the page
		return view('sanatorium/inputs::groups.form', compact('mode', 'group'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the group
		list($messages) = $this->groups->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/inputs::groups/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.inputs.groups.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
