<?php

namespace Stario\Iwrench\FileManager;

use Illuminate\Http\Request;

class Uploader extends BaseManager
{
    public function upload(Request $request)
    {
        // TODO: request接收参数，从而确定上传文件类型(封面图、文章图片、公文文件等)放到不同文件夹下
        return $this->store($request->file('file'), 'cover');
    }
}
