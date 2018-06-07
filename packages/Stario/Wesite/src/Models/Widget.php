<?php

namespace Stario\Wesite\Models;

use Illuminate\Database\Eloquent\Model;
use Stario\Wesite\Models\Post;

/**
 * Widget
 */
class Widget extends Model
{
    protected $table = 'wesite_widgets';
    protected $hidden = ['pivot'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'wesite_widget_post', 'widget_id', 'post_id');
    }
    public function postsPublished()
    {
        return $this->belongsToMany(Post::class, 'wesite_widget_post', 'widget_id', 'post_id')->published();
    }
    // 主题图 id:1
    public function scopeSwipePosts()
    {
        return $this->find(1)->posts()->published()->orderBy('updated_at', 'desc')->limit(2);
    }
    // 推荐 id:2
    public function scopeRecommendPosts()
    {
        return $this->find(2)->posts()->published()->orderBy('updated_at', 'desc')->limit(6);
    }
    // 滚动 id: 3
    public function scopeHeadlinePosts()
    {
        return $this->find(3)->posts()->published()->orderBy('updated_at', 'desc')->limit(5);
    }
}
