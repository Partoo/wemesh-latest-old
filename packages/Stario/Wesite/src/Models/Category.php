<?php
namespace Stario\Wesite\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'wesite_categories';
    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function postsPublished()
    {
        return $this->hasMany(Post::class)->published();
    }

    /**
     * @return mixed
     */
    public function paginatedPosts()
    {
        return $this->posts()->published()->orderBy('published_at', 'desc');
    }
}
