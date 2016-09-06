<?php namespace Sanatorium\Inputs\Handlers\Form;

use Sanatorium\Inputs\Models\Form;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface FormEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a form is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a form is created.
	 *
	 * @param  \Sanatorium\Inputs\Models\Form  $form
	 * @return mixed
	 */
	public function created(Form $form);

	/**
	 * When a form is being updated.
	 *
	 * @param  \Sanatorium\Inputs\Models\Form  $form
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Form $form, array $data);

	/**
	 * When a form is updated.
	 *
	 * @param  \Sanatorium\Inputs\Models\Form  $form
	 * @return mixed
	 */
	public function updated(Form $form);

	/**
	 * When a form is being deleted.
	 *
	 * @param  \Sanatorium\Inputs\Models\Form  $form
	 * @return mixed
	 */
	public function deleting(Form $form);

	/**
	 * When a form is deleted.
	 *
	 * @param  \Sanatorium\Inputs\Models\Form  $form
	 * @return mixed
	 */
	public function deleted(Form $form);

}
