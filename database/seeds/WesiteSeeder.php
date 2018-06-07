<?php

use Illuminate\Database\Seeder;

class WesiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {

// categories
        $categories = [
            [
                'name' => '页面结构',
            ],
            [
                'name' => '健康胶东',
                'children' => [
                    ['name' => '医院介绍'],
                    ['name' => '特色科室'],
                    ['name' => '家庭医生'],
                    ['name' => '品牌建设'],
                ],
            ],
            [
                'name' => '健康资讯',
                'children' => [
                    ['name' => '医院动态'],
                    ['name' => '健康教育'],
                    ['name' => '健康知识'],
                ],
            ],
            [
                'name' => '健康服务',
                'children' => [
                    ['name' => '健康查体'],
                    ['name' => '预约挂号'],
                ],
            ],
        ];

        $menus = [
            [
                'name' => '微站首页',
                'icon' => 'home',
                // 'order' => 1,
                'url' => '/',
            ],
            [
                'name' => '我的资料',
                'icon' => 'user-circle-o',
                // 'order' => 2,
                'url' => '/me',
            ],
        ];

        $widgets = [
            [
                'name' => '主题图',
            ],
            [
                'name' => '首页推荐',
            ],
            [
                'name' => '滚动公告',
            ],
            [
                'name' => '栏目推荐',
            ],
        ];

        Schema::disableForeignKeyConstraints();
        DB::table('wesite_categories')->delete();
        Schema::enableForeignKeyConstraints();

        foreach ($categories as $category) {
            Stario\Wesite\Models\Category::create($category);
        }

        foreach ($menus as $menu) {
            Stario\Wesite\Models\WesiteMenu::create($menu);
        }

        foreach ($widgets as $widget) {
            Stario\Wesite\Models\Widget::create($widget);
        }

        DB::table('wesite_posts')->delete();
        factory(Stario\Wesite\Models\Post::class, 40)
            ->create()
            ->each(function ($p) {
                $p->widgets()->save(Stario\Wesite\Models\Widget::find(rand(1, 3)));
            });
    }

}
