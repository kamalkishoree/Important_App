<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTeamTagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_team_tags', function(Blueprint $table)
		{
			$table->id();
			$table->bigInteger('task_id')->unsigned()->nullable();
			$table->bigInteger('tag_id')->unsigned()->nullable();
			$table->timestamps();
		});

		Schema::table('task_team_tags', function (Blueprint $tab) {
			$tab->foreign('task_id')->references('id')->on('tasks')->onUpdate('cascade')->onDelete('cascade');
			$tab->foreign('tag_id')->references('id')->on('tags')->onUpdate('cascade')->onDelete('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_team_tags');
	}

}
