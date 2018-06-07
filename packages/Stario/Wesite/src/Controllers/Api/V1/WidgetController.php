<?php

namespace Stario\Wesite\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stario\Icenter\Models\Admin;
use Stario\Wesite\Models\Category;
use Stario\Wesite\Models\Widget;
use Stario\Wesite\Resources\Widget as WidgetResource;

class WidgetController extends Controller
{

    protected $widget;

    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
    }

    /**
     * @api {get} /wesite/admin/widget 获取post列表
     * @apiDescription 获取widget列表，并根据参数返回附加信息
     * @apiGroup Wesite
     * @apiPermission wesite manager
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200
     *     {
     *        data: [...],
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401
     *     {
     *       messages[...]
     *     }
     */
    public function index()
    {
        return WidgetResource::collection(Widget::all());
    }
    /**
     * @api {get} /wesite/admin/widget/id 获取指定 ID widget 信息
     * @apiDescription 获取指定 ID widget 信息
     * @apiGroup Wesite
     * @apiPermission wesite manager
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200
     *     {
     *          content
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401
     *     {
     *       content
     *     }
     */
    public function show($id)
    {
        return new WidgetResource($this->widget->find($id));
    }
    /**
     * @api {widget} /wesite/admin/widget 创建一篇文章
     * @apiDescription 创建一篇文章
     * @apiGroup Wesite
     * @apiPermission wesite manager
     * @apiParam {String} title 文章标题
     * @apiParam {Number} category_id 分类ID
     * @apiParam {String} content 文章内容
     * @apiParam {Number} status 文章状态，0: 回收站 1: 已发布 2: 草稿
     * @apiParam {Boolean} is_page 是否可以直接被菜单调用，不指定则自动为否
     * @apiParam {String} content 文章内容
     * @apiParam {Number} widget id 可选文章类型(如首页推荐、主题图、滚动等)， 与widgets表相对应
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200
     *     {
     *          content
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401
     *     {
     *       content
     *     }
     */
    public function store(Request $request)
    {
        // $post = $this->post->create([
        //     'title' => $request->input('title'),
        //     'category_id' => $request->input('categoryId'),
        //     'content' => $request->input('content'),
        //     'status' => $request->input('status'),
        //     'is_page' => $request->has('is_page') ? $request->input('is_page') : 0,
        //     'author_id' => Auth::user()->id,
        // ]);
        // if ($post) {
        //     if (!empty($request->input('types'))) {
        //         $post->widgets()->sync($request->input('types'));
        //     }
        //     return Reaction::withsuccess();
        // }
        // Reaction::withBadRequest('文章未能成功创建');
    }

    /**
     * @api {put} /wesite/admin/post/id 修改指定文章
     * @apiDescription 修改指定文章
     * @apiGroup Wesite
     * @apiPermission wesite manager
     * @apiParam {String} title 文章标题
     * @apiParam {Number} category_id 分类ID
     * @apiParam {String} content 文章内容
     * @apiParam {Number} status 文章状态，0: 回收站 1: 已发布 2: 草稿
     * @apiParam {Boolean} is_page 是否可以直接被菜单调用，不指定则自动为否
     * @apiParam {String} content 文章内容
     * @apiParam {Number} widget id 可选文章类型(如首页推荐、主题图、滚动等)， 与widgets表相对应
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200
     *     {
     *          content
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401
     *     {
     *       content
     *     }
     */
    // public function update(PostRequest $request, $id)
    // {
    //     $post = $this->post->find($id)
    //         ->update([
    //             'title' => $request->input('title'),
    //             'content' => $request->input('content'),
    //             'status' => $request->input('status'),
    //             'is_page' => $request->has('is_page') ? $request->input('is_page') : 0,
    //             'category_id' => $request->input('categoryId'),
    //             'author_id' => Auth::user()->id,
    //         ]);
    //     return $this->post->find($id)->widgets()->sync($request->input('types'));
    // }

    /**
     * @api {delete} /wesite/admin/post/id 删除指定文章
     * @apiDescription 删除指定文章
     * @apiGroup Wesite
     * @apiPermission wesite manager
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200
     *     {
     *          content
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401
     *     {
     *       content
     *     }
     */
    // public function destroy(Request $request)
    // {
    //     $count = 0;
    //     $ids = $request->ids;
    //     foreach ($ids as $id) {
    //         $this->post->where('id', $id)->delete(['status' => request()->input('status')]);
    //         $count++;
    //     }
    //     return Reaction::withsuccess('成功删除' . $count . '条操作');
    // }
}
