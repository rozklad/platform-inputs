<?php namespace Sanatorium\Inputs\Repositories\Form;

interface FormRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Inputs\Models\Form
	 */
	public function grid();

	/**
	 * Returns all the inputs entries.
	 *
	 * @return \Sanatorium\Inputs\Models\Form
	 */
	public function findAll();

	/**
	 * Returns a inputs entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Inputs\Models\Form
	 */
	public function find($id);

	/**
	 * Determines if the given inputs is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given inputs is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given inputs.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a inputs entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Inputs\Models\Form
	 */
	public function create(array $data);

	/**
	 * Updates the inputs entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Inputs\Models\Form
	 */
	public function update($id, array $data);

	/**
	 * Deletes the inputs entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
