<?php

namespace Stario\Icenter\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Auth;
use Stario\Icenter\Resources\Admin as AdminResource;

class MeController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index()
    {
        return new AdminResource($this->user);
    }
}
