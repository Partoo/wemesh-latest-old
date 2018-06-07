<?php

use Illuminate\Database\Seeder;
use Stario\Icenter\Models\Admin;
use Stario\Icenter\Models\Permission;
use Stario\Icenter\Models\Profile;
use Stario\Icenter\Models\Role;
use Stario\Icenter\Models\Unit;
use Stario\Icenter\Services\Icenter;

class IcenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 创建部门
        $office = Unit::create([
            'name' => '办公室',
        ]);
        //根据icenter模块机制（.json文件）创建菜单及权限
        Icenter::makeMenus();

        //创建管理员角色
        $adminRole = Role::create([
            'name' => 'root',
            'label' => '管理员',
        ]);

        $userRole = Role::create([
            'name' => 'manager',
            'label' => '普通管理人员',
        ]);

        $permissionList = Permission::all();
        $userRole->givePermissionTo('general');

        foreach ($permissionList as $permission) {
            $adminRole->givePermissionTo($permission['name']);
        }
        // 创建默认管理员
        $admin = Admin::create([
            'name' => '刘德华',
            'mobile' => '18688889999',
            'password' => bcrypt('password'),
            'email' => 'admin@stario.net',
        ]);
        $partoo = Admin::create([
            'name' => '郭富城',
            'mobile' => '18669783161',
            'password' => bcrypt('password'),
            'email' => 'partoo@163.com',
        ]);

        $admin->assignRole('root');
        $partoo->assignRole('manager');

        // 关联用户和部门
        $office->admins()->save($admin);

        // 创建一个用户资料
        $profile = Profile::create([
            'nickname' => 'Partoo',
            'avatar' => 'http://tva3.sinaimg.cn/crop.0.0.996.996.180/7b9ce441jw8f6jzisiqduj20ro0roq4k.jpg',
            'sex' => '男',
            'birthplace' => 'LA',
            'qq' => '123321',
            'admin_id' => 1
        ]);
        //关联用户和资料
        $admin->profile()->save($profile);

    }
}
