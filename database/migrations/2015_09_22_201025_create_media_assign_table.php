<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaAssignTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if ( Schema::hasTable('media_assign') )
			return true;
		
		Schema::create('media_assign', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('entity_id');
			$table->string('entity_type');
			$table->integer('media_id');
			$table->string('slug')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('media_assign');
	}

}