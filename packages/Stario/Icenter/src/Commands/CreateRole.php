<?php

namespace Stario\Icenter\Commands;

use Illuminate\Console\Command;
use Stario\Icenter\Contracts\Role as RoleContract;

class CreateRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ic:role
                {name : The name of the role}
                {guard? : The name of the guard}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a role';
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $roleClass = app(RoleContract::class);

        $role = $roleClass::create([
            'name' => $this->argument('name'),
            'guard_name' => $this->argument('guard'),
        ]);

        $this->info("Role `{$role->name}` created");
    }
}
