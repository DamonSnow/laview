<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Input;

class Article extends JsonResource
{

    private $detail;

    public function __construct($resource, $detail = '') {
        // Ensure we call the parent constructor
        $this->detail = $detail; // $apple param passed
        parent::__construct($resource);
        $this->resource = $resource;

    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'user'             => $this->user->name,
            'slug'             => $this->slug,
            'categories'       => $this->detail === 'detail' ? $this->categories->pluck('id') : $this->categories->pluck('name'),
            'tags'             => $this->detail === 'detail' ? $this->tags : $this->tags->pluck('name'),
            'view_count'       => $this->view_count,
            'vote_count'       => $this->vote_count,
            'comment_count'    => $this->comment_count,
            'collection_count' => $this->collection_count,
            'enable'           => $this->enable,
            'publish_at'       => $this->publish_at,
            'created_at'       => $this->created_at,
            'descriptions'     => $this->when($this->detail === 'detail', $this->descriptions),
            'content'          => $this->when($this->detail === 'detail', $this->content),
            'cover_image'      => $this->when($this->detail === 'detail', $this->cover_image),
            'access_type'      => $this->when($this->detail === 'detail', $this->access_type),
            'access_value'      => $this->when($this->detail === 'detail', $this->access_value),
        ];
    }
}
