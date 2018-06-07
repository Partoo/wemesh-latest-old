<?php

namespace Stario\Icenter\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Menu extends Resource
{
    /**
     * Resource for admin
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $arr = [
            'name' => $this->name,
        ];
        // if (request()->input('include') === 'menu') {
        //     $arr['menu'] = $this->modules();
        // }

        return $arr;
    }
}
