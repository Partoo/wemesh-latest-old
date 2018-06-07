<?php

namespace Stario\Wesite\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stario\Iwrench\Reaction\Reaction;
use Stario\Wesite\Models\Category;
use Stario\Wesite\Models\WesiteMenu;
use Stario\Wesite\Requests\MenuRequest;
use Stario\Wesite\Resources\WesiteMenu as MenuResource;

class MenuController extends Controller
{

    protected $menu;

    public function __construct(WesiteMenu $menu)
    {
        $this->menu = $menu;
    }

    public function index()
    {
        // 前台需要传递与WesiteMenu Model Scope同名的type， ?type=main或 ?type=quick
        $type = request()->input('type');
        return MenuResource::collection($this->menu->{$type}()->orderBy('order')->get())->additional([
            'categories' => Category::select('name', 'id')->get(),
        ]);
    }

    public function store(MenuRequest $request)
    {
        $menu = $this->menu->create([
            'url' => $request->input('url'),
            'icon' => $request->input('icon'),
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'order' => $request->input('order'),
        ]);
        if ($menu->save()) {
            return Reaction::withsuccess();
        } else {
            return Reaction::withBadRequest();
        }
    }

    public function update(MenuRequest $request)
    {
        $update = $this->menu->find($request->input('id'))->update([
            'url' => $request->input('url'),
            'icon' => $request->input('icon'),
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'order' => $request->input('order'),
        ]);
        if ($update) {
            return Reaction::withsuccess();
        }
        return Reaction::withBadRequest();
    }
    public function destroy($id)
    {
        return $this->menu->destroy($id);
    }

    public function reorder(Request $request)
    {
        $type = request()->input('type');
        $orders = request()->input('order');
        $this->menu->setNewOrder($orders);
    }
}
