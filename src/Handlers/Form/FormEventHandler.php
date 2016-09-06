<?php namespace Sanatorium\Inputs\Handlers\Form;

use Illuminate\Events\Dispatcher;
use Sanatorium\Inputs\Models\Form;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class FormEventHandler extends BaseEventHandler implements FormEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.inputs.form.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.inputs.form.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.inputs.form.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.inputs.form.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.inputs.form.deleted', __CLASS__.'@deleting');
		$dispatcher->listen('sanatorium.inputs.form.deleted', __CLASS__.'@deleted');
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
	public function created(Form $form)
	{
		$this->flushCache($form);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Form $form, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Form $form)
	{
		$this->flushCache($form);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleting(Form $form)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Form $form)
	{
		$this->flushCache($form);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Inputs\Models\Form  $form
	 * @return void
	 */
	protected function flushCache(Form $form)
	{
		$this->app['cache']->forget('sanatorium.inputs.form.all');

		$this->app['cache']->forget('sanatorium.inputs.form.'.$form->id);
	}

}
