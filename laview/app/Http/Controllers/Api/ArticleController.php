<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticleController extends ApiController
{
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
                DB::insert('insert into article_tags (article_id, tag_id) values (?, ?)', [$article->id, $tag]);
            }
            foreach ($request->input('categories') as $category) {
                DB::insert('insert into article_categories (article_id, category_id) values (?, ?)', [$article->id, $category]);
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
}
