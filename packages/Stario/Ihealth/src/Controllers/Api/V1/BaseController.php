<?php

namespace Stario\Ihealth\Controllers\Api\V1;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->scope();
    }

    // 指定post的scope
    abstract public function scope();

    public function index()
    {

    }
}
