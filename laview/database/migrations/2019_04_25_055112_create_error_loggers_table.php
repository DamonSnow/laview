<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorLoggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_loggers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code')->comment('错误编码');
            $table->string('mes')->comment('错误信息');
            $table->string('type')->comment('错误类型');
            $table->string('url')->comment('发生错误url');
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
        Schema::dropIfExists('error_loggers');
    }
}
