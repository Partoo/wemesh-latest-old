<?php
namespace Stario\Wesite\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Stario\Icenter\Models\Admin;
use Stario\Wesite\Models\Widget;

class Post extends Model
{
    protected $casts = [
        'meta' => 'array',
    ];
    protected $table = 'wesite_posts';
    protected $hidden = ['pivot'];

    protected $guarded = ['id'];

    /**
     * Carbon instance fields
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function widgets()
    {
        return $this->belongsToMany(Widget::class, 'wesite_widget_post', 'post_id', 'widget_id');
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }

    public function type()
    {
        return $this->widgets()->get(['id']);
    }

    // 0:草稿 1:已发布 2:回收站
    public function scopeDraft($query)
    {
        return $query->where('status', '=', 0);
    }
    public function scopePublished($query)
    {
        return $query->where('status', '=', 1);
    }
    public function scopeTrash($query)
    {
        return $query->where('status', '=', 2);
    }

    public function scopePost($query)
    {
        return $query->where('type', '=', 'post');
    }
    public function scopePage($query)
    {
        return $query->where('type', '=', 'page');
    }
    public function scopeVideo($query)
    {
        return $query->where('type', '=', 'video');
    }

}
