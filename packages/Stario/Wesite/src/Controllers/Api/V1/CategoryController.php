<?php

namespace Stario\Wesite\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Stario\Iwrench\Reaction\Reaction;
use Stario\Wesite\Models\Category;
use Stario\Wesite\Requests\CategoryRequest;
use Stario\Wesite\Resources\Category as CategoryResource;

class CategoryController extends Controller
{

    protected $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        return cache('wesite_category');
    }
    public function show($id)
    {
        return new CategoryResource(Category::find($id));
    }
    public function store(CategoryRequest $request)
    {
        if (empty($request->input('parentId'))) {
            return $this->category->create(['name' => $request->input('name')]);
        }
        $this->category->name = $request->input('name');
        $this->category->parent_id = $request->input('parentId');
        if ($this->category->save()) {
            return response()->json('分类创建成功');
        }
        return Reaction::withError('分类未能成功创建');
    }
    public function update(CategoryRequest $request)
    {
        if ($this->category->find($request->input('id'))
            ->update(['name' => $request->input('name')])) {
            return response()->json('分类修改成功');
        }
        return Reaction::withForbidden('分类未能修改');
    }
    public function destroy($id)
    {
        try {
            $this->category->find($id)->delete();
            return response()->json('分类删除成功');
        } catch (\Exception $e) {
            return Reaction::withForbidden('分类删除失败，请确定已清空该分类下所有的文章');
        }
    }
}
