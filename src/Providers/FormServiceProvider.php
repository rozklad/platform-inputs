<?php namespace Sanatorium\Inputs\Providers;

use Cartalyst\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.inputs.form.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.inputs.form', 'Sanatorium\Inputs\Repositories\Form\FormRepository');

		// Register the data handler
		$this->bindIf('sanatorium.inputs.form.handler.data', 'Sanatorium\Inputs\Handlers\Form\FormDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.inputs.form.handler.event', 'Sanatorium\Inputs\Handlers\Form\FormEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.inputs.form.validator', 'Sanatorium\Inputs\Validator\Form\FormValidator');
	}

}
