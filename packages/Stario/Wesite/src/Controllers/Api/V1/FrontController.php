<?php
namespace Stario\Wesite\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Stario\Wesite\Models\Category;
use Stario\Wesite\Models\Post;
use Stario\Wesite\Models\WesiteMenu;
use Stario\Wesite\Models\Widget;
use Stario\Wesite\Resources\Category as CategoryResource;
use Stario\Wesite\Resources\Post as PostResource;
use Stario\Wesite\Resources\WesiteMenu as MenuResource;

class FrontController extends Controller
{
    public function home()
    {
        // TODO: 加入缓存
        // 底部菜单
        $nav = MenuResource::collection(WesiteMenu::main()->orderBy('order')->get());
        // 快捷导航
        $guide = MenuResource::collection(WesiteMenu::quick()->orderBy('order')->get());
        // 主题图
        $swipe = Widget::swipePosts()->get();
        // 滚动
        $headline = Widget::headlinePosts()->get();
        // 推荐
        $recommend = Widget::recommendPosts()->get();

        return response()->json([
            'data' => [
                'nav' => $nav,
                'guide' => $guide,
                'swipe' => $swipe,
                'headline' => $headline,
                'recommend' => $recommend,
            ],
        ]);
    }
    public function category($id)
    {
        return new CategoryResource(Category::with('postsPublished')->find($id));
    }
    public function post($id)
    {
        return new PostResource(Post::find($id));
    }
}
