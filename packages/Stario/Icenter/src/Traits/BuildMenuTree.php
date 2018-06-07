<?php
namespace Stario\Icenter\Traits;

/**
 * 用于生成菜单resource
 */
trait BuildMenuTree
{
    /**
     * 生成最终的菜单
     * TODO: 比较傻的挨个遍历，待优化
     * @return collect
     */
    protected function getMenu()
    {
        $result = [];
        foreach ($this->getRaw() as $key => $item) {
            if ($item->parent_id == null) {
                $result[$key] = $item;
                $children = $this->getChildren()->where('parent_id', $item->id);
                if ($children->isNotEmpty()) {
                    $result[$key]['children'] = $children;
                }
            }
        }
        // 用PHP 7.0 特性 <=> 排序
        usort($result, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });
        return $result;
    }
    /**
     * 使用laravel-permission的getAllPermissions方法生成当前用户的权限菜单
     */
    protected function getRaw()
    {
        return $this->getAllPermissions()
            ->map(function ($permission) {
                return $permission->menus;
            })
            ->flatten();
    }

    /**
     * 使用laravel-nestedset 获取所有的子菜单，并剔除空数组
     */

    protected function getChildren()
    {
        return $this->getRaw()->map(function ($item) {
            return $item->descendants;
        })->reject(function ($r) {
            return $r->isEmpty();
        })->flatten();
    }

}
