<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title', 100)->default('')->comment('文章标题');
            $table->string('slug', 100)->default('')->nullable()->comment('slug');
            $table->string('keywords', 255)->default('')->nullable()->comment('关键词,以英文逗号隔开');
            $table->string('descriptions', 255)->default('')->comment('描述');
            $table->integer('cover_image')->default(0)->nullable()->comment('封面图片');
            $table->text('content')->comment('内容markdown格式');
            $table->text('content_html')->comment('内容html格式');
            $table->integer('user_id')->default(0)->comment('作者 id');
            $table->unsignedInteger('view_count')->default(0)->comment('查看数量');
            $table->unsignedInteger('vote_count')->default(0)->comment('点赞数量');
            $table->unsignedInteger('comment_count')->default(0)->comment('评论数量');
            $table->unsignedInteger('collection_count')->default(0)->comment('收藏数量');
            $table->unsignedTinyInteger('enable')->default(1)->comment('启用状态：0禁用,1草稿，2启用');
            $table->unsignedTinyInteger('recommend')->default(0)->comment('是否推荐到首页');
            $table->unsignedTinyInteger('top')->default(0)->comment('是否置顶');
            $table->integer('weight')->default(20)->comment('权重');
            $table->tinyInteger('access_type')->default(1)->comment('访问权限类型：1公开、2私密、3密码访问');
            $table->string('access_value',255)->default('')->nullable()->comment('访问权限值：1->不公开的用户ids,2->公开的用户ids,3->访问密码');
            $table->string('created_year',4)->default('')->comment('创建年：2018');
            $table->string('created_month',4)->default('')->comment('01');
            $table->timestamp('publish_at')->comment('发布时间');
            $table->timestamps();
            $table->index('weight');
            $table->index('category_id');
            $table->index('user_id');
            $table->index('created_year');
            $table->index('created_month');
            $table->index('access_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
