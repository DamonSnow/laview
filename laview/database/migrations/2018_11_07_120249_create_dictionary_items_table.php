<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictionaryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionary_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->comment('字典类型id');
            $table->string('item_name')->comment('字典项目名称');
            $table->string('item_value',50)->comment('字典项目值');
            $table->tinyInteger('enable')->default('1')->comment('是否可用');
            $table->integer('sort')->comment('排序');
            $table->string('comment')->nullable()->comment('备注');
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
        Schema::dropIfExists('dictionary_items');
    }
}
