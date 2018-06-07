<?php

namespace Stario\Icenter\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Auth;
use Stario\Icenter\Models\Menu;
use Stario\Icenter\Resources\Menu as MenuResource;

class MenuController extends Controller
{

    protected $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function index()
    {
        return Auth::user()->menus();
    }
    public function show($id)
    {
        return new MenuResource(Menu::find($id));
    }
    public function store(Request $request)
    {
        dd($request->all());
    }
}
