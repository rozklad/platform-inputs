<?php namespace Sanatorium\Inputs\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Inputs\Repositories\Form\FormRepositoryInterface;
use Sanatorium\Inputs\Repositories\Group\GroupRepositoryInterface;

class FormsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Inputs repository.
	 *
	 * @var \Sanatorium\Inputs\Repositories\Form\FormRepositoryInterface
	 */
	protected $forms;

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
	 * @param  \Sanatorium\Inputs\Repositories\Form\FormRepositoryInterface  $forms
     * @param  \Sanatorium\Inputs\Repositories\Group\GroupRepositoryInterface  $groups
	 * @return void
	 */
	public function __construct(FormRepositoryInterface $forms, GroupRepositoryInterface $groups)
	{
		parent::__construct();

		$this->forms = $forms;

        $this->groups = $groups;
	}

	/**
	 * Display a listing of form.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/inputs::forms.index');
	}

	/**
	 * Datasource for the form Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->forms->grid();

		$columns = [
			'id',
			'entity',
			'settings',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.inputs.forms.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new form.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new form.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating form.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating form.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified form.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->forms->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/inputs::forms/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.inputs.forms.all');
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
				$this->forms->{$action}($row);
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
		// Do we have a form identifier?
		if (isset($id))
		{
			if ( ! $form = $this->forms->find($id))
			{
				$this->alerts->error(trans('sanatorium/inputs::forms/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.inputs.forms.all');
			}
		}
		else
		{
			$form = $this->forms->createModel();
		}

		// @todo make manager repository
		$entities = [
            'Sleighdogs\Profile\Models\Profile' => 'Profile'
        ];

        $groups = $this->groups->get();

        $conditionals = [];

        // @todo make dynamic
        $profile_attribute = \Platform\Attributes\Models\Attribute::where('slug', 'profile')->first();
        if ( is_object($profile_attribute) )
        {
            $conditionals['profile'] = [
                'attribute' => $profile_attribute,
                'options' => $profile_attribute->options
            ];
        }

		// Show the page
		return view('sanatorium/inputs::forms.form', compact('mode', 'form', 'entities', 'groups', 'conditionals'));
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
		// Store the form
		list($messages) = $this->forms->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/inputs::forms/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.inputs.forms.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
