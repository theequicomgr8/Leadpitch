<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demos', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('lead_id')->nullable();
			$table->string('name');
			$table->string('email');
			$table->string('mobile');
			$table->integer('source')->unsigned();
			$table->string('source_name');
			$table->integer('course')->unsigned();
			$table->string('course_name');
			$table->string('sub_courses');
			$table->integer('status')->unsigned();
			$table->string('status_name');
			$table->tinyInteger('demo_attended');
			$table->string('executive_call');
			$table->string('demo_type',50)->default('Calling');
			$table->integer('owner')->unsigned();
			$table->text('remarks');
			$table->softDeletes();
			$table->integer('deleted_by')->unsigned();
			$table->integer('created_by')->unsigned();
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
        Schema::drop('demos');
    }
}
