<?php
namespace Stario\Wesite\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Stario\Wesite\Models\Post;

class PostObserver
{
    protected $post;

    public function creating(Post $post)
    {
        $name = empty(Auth::user()->name) ? '系统' : Auth::user()->name;
        $post->meta = $name . '于' . Carbon::parse($post->created_at)->format('Y-m-d H:i:s') . '创建';
    }

    public function saving(Post $post)
    {
        dd($post->meta);
        $name = empty(Auth::user()->name) ? '系统' : Auth::user()->name;
        $post->meta = $name . '于' . Carbon::now()->format('Y-m-d H:i:s') . '修改';
    }
}
