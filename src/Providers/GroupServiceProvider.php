<?php namespace Sanatorium\Inputs\Providers;

use Cartalyst\Support\ServiceProvider;

class GroupServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Inputs\Models\Group']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.inputs.group.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.inputs.group', 'Sanatorium\Inputs\Repositories\Group\GroupRepository');

		// Register the data handler
		$this->bindIf('sanatorium.inputs.group.handler.data', 'Sanatorium\Inputs\Handlers\Group\GroupDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.inputs.group.handler.event', 'Sanatorium\Inputs\Handlers\Group\GroupEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.inputs.group.validator', 'Sanatorium\Inputs\Validator\Group\GroupValidator');
	}

}
