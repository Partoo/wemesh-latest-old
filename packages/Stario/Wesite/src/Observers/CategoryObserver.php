<?php
namespace Stario\Wesite\Observers;

use Cache;
use Stario\Wesite\Models\Category;
use Stario\Wesite\Models\Widget;
use Stario\Wesite\Resources\Category as CategoryResource;
use Stario\Wesite\Resources\Widget as WidgetResource;

class CategoryObserver
{
    protected $category;

    public function deleted(Category $category)
    {
        $this->refreshCache();
    }
    public function saved(Category $category)
    {
        $this->refreshCache();
    }

    protected function refreshCache()
    {
        // 根据设计，文章不可以直接放在有子分类的分类下，所以需要判断一下
        // 播种时自动加入"页面结构"分类，id为1，页面结构分类用于标识post为页面的类型
        $raw = Category::get()
            ->reject(function ($item) {
                return $item->id === 1;
            })
            ->toTree();
        // 所有的children
        $children = $raw->filter(function ($item) {
            return $item->children->isNotEmpty();
        })->map(function ($i) {
            return $i->children;
        })->flatten();
        // 所有包含子栏目的一级栏目
        $parentHasChildren = $raw->filter(function ($item) {
            return $item->children->isEmpty();
        });
        // 所有的一级栏目
        $parent = $raw->filter(function ($item) {
            return $item->has('children');
        });
        $result = [
            'available' => CategoryResource::collection($parentHasChildren)->merge(CategoryResource::collection($children)),
            'parent' => CategoryResource::collection($parent),
        ];
        // 文章编辑添加时需要同时调取文章分类、文章状态及文章的类型
        if (request()->input('include') === 'types') {
            $r = array_merge($result, [
                'types' => WidgetResource::collection(Widget::all()),
            ]);
            Cache::forever('wesite_category', $r);
            // return $r;
        } else {
            Cache::forever('wesite_category', $result);
            // return $result;
        }
    }
}
