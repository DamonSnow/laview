<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Http\Resources\Article AS ArticleResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ArticleController extends ApiController
{
    public function index()
    {
        $article = new Article();
        return ArticleResource::collection($article->paginate(Input::get('size') ?: 20));
    }

    public function show($id)
    {
        return new ArticleResource(Article::find($id), 'detail');
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descriptions' => 'required',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $time = Carbon::now();

            $article = Article::create([
                'title' => $request->input('title'),
                'keywords' => '',
                'descriptions' =>$request->input('descriptions'),
                'cover_image' => $request->input('cover_image'),
                'content' => $request->input('content'),
                'enable' => $request->input('enable'),
                'access_type' => $request->input('access_type'),
                'access_value'  => $request->input('access_value'),
                'created_year' => $time->year,
                'created_month' => $time->month,
                'user_id' => auth('api')->id(),
                'publish_at' => $request->input('publish_at')
            ]);

            if(!$article) throw new \Exception('新增文章事变',10001);
            foreach ($request->input('tags') as $tag) {
                DB::insert('insert into article_tag (article_id, tag_id) values (?, ?)', [$article->id, $tag]);
            }
            foreach ($request->input('categories') as $category) {
                DB::insert('insert into article_category (article_id, category_id) values (?, ?)', [$article->id, $category]);
            }
            DB::commit();
            return $this->success('新增文章成功', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descriptions' => 'required',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return failed_response($validator->errors()->toArray(), 'error', 1001);
        }
        DB::beginTransaction();
        try {
            $article = Article::find($id);
//            $article->update($request->all());
            if ($article->title != $request->input('title')) $article->title = $request->input('title');
            if ($article->descriptions != $request->input('descriptions')) $article->descriptions = $request->input('descriptions');
            if ($article->cover_image != $request->input('cover_image')) $article->cover_image = $request->input('cover_image');
            if ($article->content != $request->input('content')) $article->content = $request->input('content');
            if ($article->enable != $request->input('enable')) $article->enable = $request->input('enable');
            if ($article->access_type != $request->input('access_type')) $article->access_type = $request->input('access_type');
            if ($article->access_value != $request->input('access_value')) $article->access_value = $request->input('access_value');
            if ($article->publish_at != $request->input('publish_at')) $article->publish_at = $request->input('publish_at');
            if(!$article->save()) throw new \Exception('更新文章失败',1002);
            DB::delete('delete from article_tag WHERE article_id=?',[$id]);
            DB::delete('delete from article_category WHERE article_id=?',[$id]);
            foreach ($request->input('tags') as $tag) {
                DB::insert('insert into article_tag (article_id, tag_id) values (?, ?)', [$article->id, $tag]);
            }
            foreach ($request->input('categories') as $category) {
                DB::insert('insert into article_category (article_id, category_id) values (?, ?)', [$article->id, $category]);
            }
            DB::commit();
            return $this->success('update', 'success');
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = $e->getMessage();
            $code = $e->getCode();
            return $this->setStatusCode($code)->failed($msg);
        }
    }
}
