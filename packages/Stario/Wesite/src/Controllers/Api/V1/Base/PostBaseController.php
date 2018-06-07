<?php

namespace Stario\Wesite\Controllers\Api\V1\Base;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Stario\Icenter\Models\Admin;
use Stario\Iwrench\Reaction\Reaction;
use Stario\Wesite\Models\Category;
use Stario\Wesite\Models\Post;
use Stario\Wesite\Requests\PostRequest;
use Stario\Wesite\Resources\Post as PostResource;

abstract class PostBaseController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this->model = $this->scope();
    }

    // 指定post的scope
    abstract public function scope();

    /**
     * @api {get} /wesite/admin/post 获取post列表
     * @apiDescription 获取post列表，并根据参数返回附加信息
     * @apiGroup Wesite
     * @apiPermission wesite manager
     * @apiParam {String} include=statistics 统计信息，包括文章总数、已发布文章数、草稿箱及回收站文章数
     * @apiParam {String} only=published/trash/draft 分别只获取已发布/回收站/草稿
     * @apiParam {String} keyby=keyword 检索文章
     * @apiParam {String} orderby=col 按指定列名排序
     * @apiParam {String} cat=cat_id 按分类检索
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200
     *     {
     *        data: [...],
     *        links: [...],
     *        meta: [...]
     *     }
     * @apiErrorExample {json} Error-Response:
     *     HTTP/1.1 401
     *     {
     *       messages[...]
     *     }
     */
    public function index()
    {
        $currentPage = request()->input('currentPage');
        $perpage = request()->input('perpage');
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        // 附加统计信息
        $request = explode('_', request()->input('filter'));
        switch ($request[0]) {
            case 'published':
                $data = $this->model->published()->orderBy('updated_at', 'desc')->paginate($perpage);
                break;
            case 'draft':
                $data = $this->model->draft()->orderBy('updated_at', 'desc')->paginate($perpage);
                break;
            case 'trash':
                $data = $this->model->trash()->orderBy('updated_at', 'desc')->paginate($perpage);
                break;
            case 'cid':
                $cid = (int) $request[1];
                $data = Category::find($cid)->posts()->orderBy('updated_at', 'desc')->paginate($perpage);
                break;
            case 'aid':
                $aid = (int) $request[1];
                $data = Admin::find($aid)->posts()->orderBy('updated_at', 'desc')->paginate($perpage);
                break;
            default:
                $data = $this->model->orderBy('updated_at', 'desc')->paginate($perpage);
                break;
        }

        if (request()->has('keyby')) {
            $data = $this->model
                ->where('title', 'like', '%' . request()->input('keyby') . '%')
                ->orWhere('content', 'like', '%' . request()->input('keyby') . '%')
                ->orderBy('updated_at', 'desc')
                ->paginate($perpage);
        }

        // if (request()->has('orderby')) {
        //     $data = $this->model->orderBy(request()->input('orderby'),)
        // }

        return $this->makePostList($data);
    }
    /**
     * @api {get} /wesite/admin/post/id 获取指定 ID post 信息
     * @apiDescription 获取指定 ID post 信息
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
        return new PostResource($this->model->find($id));
    }
    /**
     * @api {post} /wesite/admin/post 创建一篇文章
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
    public function store(PostRequest $request)
    {
        $data = [
            'title' => $request->input('title'),
            'category_id' => $request->input('categoryId'),
            'content' => $request->input('content'),
            'status' => $request->input('status'),
            'thumb' => $request->input('thumb'),
            'updated_at' => Carbon::parse($request->input('updated_at'))->timezone('Asia/Shanghai'),
            'author_id' => Auth::user()->id,
        ];

        if ($request->has('password')) {
            $data['password'] = $request->input('password');
        }

        $post = $this->model->create($data);

        if ($post) {
            if (!empty($request->input('types'))) {
                $post->widgets()->sync($request->input('types'));
            }
            return Reaction::withsuccess();
        }
        Reaction::withBadRequest('文章未能成功创建');
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
    public function update(PostRequest $request, $id)
    {
        $data = [
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'status' => $request->input('status'),
            'thumb' => $request->input('thumb'),
            'updated_at' => Carbon::parse($request->input('updated_at'))->timezone('Asia/Shanghai'),
            'category_id' => $request->input('categoryId'),
            'author_id' => Auth::user()->id,
        ];
        if ($request->has('password')) {
            $data['password'] = $request->input('password');
        }
        $post = $this->model->find($id)
            ->update($data);
        return $this->model->find($id)->widgets()->sync($request->input('types'));
    }

    /**
     * @api {post} /wesite/admin/post/update-status/id 更改指定指定文章状态
     * @apiDescription 更改指定指定文章状态
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
    public function updateStatus(Request $request)
    {
        $count = 0;
        $ids = $request->ids;
        $status = $request->input('status');
        if ($status === 'draft') {
            $status = 0;
        }
        if ($status === 'published') {
            $status = 1;
        }
        if ($status === 'trash') {
            $status = 2;
        }
        foreach ($ids as $id) {
            $this->model->where('id', $id)->update(['status' => $status]);
            $count++;
        }
        return Reaction::withsuccess('成功完成' . $count . '条操作');
    }

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
    public function destroy(Request $request)
    {
        $count = 0;
        $ids = $request->ids;
        foreach ($ids as $id) {
            $this->model->where('id', $id)->delete(['status' => request()->input('status')]);
            $count++;
        }
        return Reaction::withsuccess('成功删除' . $count . '条操作');
    }

    protected function makePostList($collection)
    {
        return PostResource::collection($collection)->additional([
            'meta' => [
                'posts_count' => $this->model->count(),
                'published_count' => $this->model->published()->count(),
                'draft_count' => $this->model->draft()->count(),
                'trash_count' => $this->model->trash()->count(),
            ],
        ]);
    }
}
