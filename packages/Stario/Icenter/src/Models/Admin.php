<?php
namespace Stario\Icenter\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Stario\Icenter\Models\Profile;
use Stario\Icenter\Models\Unit;
use Stario\Icenter\Traits\BuildMenuTree;
use Stario\Icenter\Traits\HasRoles;
use Stario\Wesite\Models\Post;

class Admin extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles, BuildMenuTree;

    // 用于role_permission组件
    protected $guard_name = 'api';

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token', 'pivot', 'created_at', 'updated_at'];

    // protected $events = [
    //     'created' => UserCreated::class,
    //     'deleted' => ModelDeleted::class,
    // ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }

    /**
     * 获取内部管理人员当前拥有权限下的所有菜单
     * @return Collection
     */
    public function menus()
    {
        return $this->getMenu();
    }

    // 使用手机作为凭据获取accessToken
    public function findForPassport($mobile)
    {
        return $this->where('mobile', $mobile)->first();
    }

    // 发送短信
    public function routeNotificationForSms()
    {
        return $this->mobile;
    }
}
