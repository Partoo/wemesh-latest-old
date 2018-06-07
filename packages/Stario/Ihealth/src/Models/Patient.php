<?php

namespace Stario\Ihealth\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Patient extends Authenticatable
{
    use Notifiable, HasApiTokens;

    // 用于role_permission组件
    protected $guard_name = 'api';

    protected $guarded = ['id'];

    protected $hidden = ['password'];

    // protected $events = [
    //     'created' => UserCreated::class,
    //     'deleted' => ModelDeleted::class,
    // ];

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    public function setAgeAttribute($birthday)
    {
        $this->attributes['age'] = Carbon::parse($birthday)->age;
    }
    // 使用手机作为凭据获取accessToken
    // public function findForPassport($mobile) {
    //     return $this->where('mobile', $mobile)->first();
    // }

    // 流动人口  Patient::floating()->get();
    public function scopeFloating($query)
    {
        return $query->where('livetype', false);
    }

    // 常住人口 Patient::resident()->get();
    // 根据目前需求，数据库里的常驻人口都是老年人，暂时不用使用下面的scope统计
    // 此处分开只为今后扩展考虑
    public function scopeResident($query)
    {
        return $query->where('livetype', true);
    }

    // 新生儿 type=0
    public function scopeBaby($query)
    {
        return $query->where('type', '=', 0);
    }
    // 老年人 type=1
    public function scopeAged($query)
    {
        return $query->where('type', '=', 1);
    }
    // 孕妇 type=2
    public function scopeGravida($query)
    {
        return $query->where('type', '=', 2);
    }

    // 按年龄
    public function scopeAgeBetween($query, $start, $end = null)
    {
        if (is_null($end)) {
            $end = $start;
        }
        $now = Carbon::now();
        $start = $now->subYears($start)->format('Y-m-d');
        $end = $now->subYears($end)->addYear()->subDay()->format('Y-m-d');
        return $query->where([
            ['birthday', '>=', $end],
            ['birthday', '<', $start],
        ]);
    }

    // 发送短信
    public function routeNotificationForSms()
    {
        return $this->mobile;
    }
}
