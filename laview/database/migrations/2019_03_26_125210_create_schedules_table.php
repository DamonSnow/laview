<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_id');
            $table->string('title');
            $table->string('body')->nullable()->default('')->commnet('计划详细内容');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->tinyInteger('is_all_day')->default(0);
            $table->tinyInteger('catg')->default(0);
            $table->tinyInteger('is_readonly')->default(0);
            $table->tinyInteger('is_visible')->default(0);
            $table->tinyInteger('is_private')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('schedules');
    }
}
