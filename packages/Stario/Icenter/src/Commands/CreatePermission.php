<?php

namespace Stario\Icenter\Commands;

use Illuminate\Console\Command;
use Stario\Icenter\Contracts\Permission as PermissionContract;

class CreatePermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ic:permission
                {name : The name of the permission}
                {guard? : The name of the guard}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a permission';
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permissionClass = app(PermissionContract::class);

        $permission = $permissionClass::create([
            'name' => $this->argument('name'),
            'guard_name' => $this->argument('guard'),
        ]);

        $this->info("Permission `{$permission->name}` created");
    }
}
