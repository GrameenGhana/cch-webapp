<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('districts',function($table)
		{
			$table->increments('id');
			$table->string('name',100);
			$table->string('region',100);
			$table->string('country',100);
			$table->boolean('deleted');
			$table->softDeletes();
			$table->timestamps();
		});
		//
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('districts');
	}

}
