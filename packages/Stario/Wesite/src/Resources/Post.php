<?php

namespace Stario\Wesite\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;
use Stario\Wesite\Models\Category;

class Post extends Resource
{
    /**
     * Resource for admin
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $arr = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'categoryId' => $this->category->id,
            'category' => $this->category->name,
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
            'thumb' => $this->thumb,
            'password' => $this->password,
            'authorId' => $this->author->id,
            'author' => $this->author->name,
            'history' => $this->when($request->input('include') === 'detail', function () {
                return $this->history;
            }),
            // TODO: 无法使用whenLoaded('widget'),目前可能有N+1问题
            'type' => $this->type(),
            // 'type' => $this->whenPivotLoaded('wesite_widget_post', function () {
            //     return $this->widgets->id;
            // }),
            'status' => $this->status === 0 ? '草稿' : ($this->status === 1 ? '已发布' : '回收站'),
            'is_page' => $this->is_page === 1 ? true : false,
        ];
        return $arr;
    }
}
