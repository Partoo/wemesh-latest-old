<?php

namespace Stario\Wesite\Resources;

use Illuminate\Http\Resources\Json\Resource;

class WesiteMenu extends Resource
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
            'icon' => $this->icon,
            'url' => $this->url,
            'type' => $this->type,
            'order' => $this->order,
        ];
    }
}
