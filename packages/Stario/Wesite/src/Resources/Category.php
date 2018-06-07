<?php

namespace Stario\Wesite\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Category extends Resource
{
    /**
     * Resource for admin
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $children = $this->children;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'posts_count' => $this->posts->count(),
            'children' => $this->when($children->isNotEmpty(), $this->collection($this->children)),
            'posts' => $this->when($request->input('include') === 'posts' && $this->posts->isNotEmpty(), Post::collection($this->posts->take(15))),
        ];
    }
}
