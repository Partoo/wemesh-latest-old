<?php

namespace Stario\Wesite\Controllers\Api\V1;

use Stario\Wesite\Controllers\Api\V1\Base\PostBaseController;
use Stario\Wesite\Models\Post;

class PostController extends PostBaseController
{
    public function scope()
    {
        return Post::post();
    }
}
