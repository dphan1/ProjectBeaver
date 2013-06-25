<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        	Schema::create('users2', function($table) {
		   $table->increments('id'); // auto incremental id (PK)
		   
		   // Create three fields, type string
		   $table->string('username', 32);
		   $table->string('email', 320);
		   $table->string('password', 64);

		   // Type integer
		   $table->integer('role');

		   // Type boolean
		   $table->boolean('active');

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
		//
		Schema::drop('users2');
	}

}
