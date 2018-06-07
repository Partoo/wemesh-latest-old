<?php

namespace Stario\Wesite\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Widget extends Resource
{
    /**
     * Resource for admin
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'posts' => $this->posts,
        ];
    }
}
