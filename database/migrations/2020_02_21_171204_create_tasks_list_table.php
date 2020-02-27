<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('to_do_lists_id');
            $table->string('task_name');
            $table->dateTime('deadline');
            $table->boolean('completed');
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
        Schema::dropIfExists('tasks_list');
    }
}
