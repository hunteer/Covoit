<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('prenomUsers', 25);
			$table->string('pseudoUsers', 25);
			$table->string('email', 25);
			$table->string('password', 60);
			$table->string('name',20);
			$table->string('telFixeUsers', 13)->nullable();
			$table->string('telPortUsers', 13);
			$table->integer('vehiculeUsers')->unsigned()->nullable();
			$table->date('dateNaissanceUsers');
			$table->string('photoUsers', 65535)->nullable();
			$table->boolean('admin')->default(false);			
			$table->rememberToken();
			$table->timestamps();
			$table->engine = 'InnoDB';
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}